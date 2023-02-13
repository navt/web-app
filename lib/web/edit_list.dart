import 'dart:html';
import 'dart:convert';
import 'package:web_app/web/any_page.dart';
import 'package:web_app/web/idb.dart';

class EditList extends AnyPage {
  
  final UListElement postsList = querySelector('#posts-list') as UListElement;

  @override
  Future<void> makeRequest() async {

    final uriString = uri?.text ?? '';
    uri?.remove();

    var token = await getToken();
    protocol = window.location.protocol;
    hostname = window.location.hostname ?? 'localhost';
    port = window.location.port;
    final path = (port != '') ? '$protocol//$hostname:$port/api/$uriString' 
                              : '$protocol//$hostname/api/$uriString';

    final httpRequest = HttpRequest();
    httpRequest.open('GET', path);
    httpRequest.setRequestHeader('App-Token', token);
    httpRequest.onLoadEnd.listen((e) => requestComplete(httpRequest));
    httpRequest.send('');  
  }

  dynamic getToken()async {
    Idb idb = Idb('Blog', 'Token');
    var db = await idb.openDB(1);
    var records = await idb.cursor(db);
    var token = records[0]['token'];
    return token;
  }
  
  @override
  void processResponse(String jsonString) {

    var jsonData = json.decode(jsonString);
    var itemJsonData = jsonData[0];

    if (itemJsonData.containsKey('error')) {
      
      if (itemJsonData['error'] == 'token missing') {
        window.open('/auth', '_parent');
      }

      h1?.text = 'Error';
      cntnt?.text = itemJsonData['error'];
      return;
    }
    
    fillPage(jsonData);
  }

  @override
  void fillPage(List<dynamic> jsonData) {
    keywords.content = 'редактирование,пост';
    description.content = 'минус означает скрытый пост';
    title?.text = 'Список для редактирования';
    h1?.text = 'Редактировать пост';
    /*
    AnchorElement buttonCreate = AnchorElement();
    buttonCreate.classes = ['uk-button', 'uk-button-default', 'uk-button-small'];
    buttonCreate.href = '$protocol//$hostname:$port/add';
    buttonCreate.text = 'Create new post';
    cntnt?.children.add(buttonCreate);
    */
    for (var i = jsonData.length - 1; i >= 0; i--) {
      final li = LIElement();

      // https://api.dart.dev/stable/2.18.2/dart-html/HtmlElement-class.html
      AnchorElement a = AnchorElement();
      final id = jsonData[i]['id'];
      final title = jsonData[i]['title'];
      a.href = '$protocol//$hostname:$port/posts/$id';
      a.text = '$title';
      li.children.add(a);
      
      if (jsonData[i]['publish'] == '1') {
        // appendText() as variant
        li.appendHtml('&nbsp;&oplus;&nbsp;');
      } else {
        li.appendHtml('&nbsp;&ominus;&nbsp;');
      }
      

      AnchorElement button = AnchorElement();
      button.classes = ['uk-button', 'uk-button-default', 'uk-button-small'];
      button.href = '$protocol//$hostname:$port/edit/$id';
      button.text = 'Edit';
      li.children.add(button);
      
      postsList.children.add(li);
    }
  }
}