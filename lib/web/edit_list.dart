import 'dart:html';
import 'package:web_app/web/any_page.dart';

class EditList extends AnyPage {
  
  final UListElement postsList = querySelector('#posts-list') as UListElement;
  
  @override
  void fillPage(List<dynamic> jsonData) {
    keywords.content = ' edit posts,list';
    description.content = 'Realy description of blog';
    title?.text = 'List for edit';
    h1?.text = 'Posts list for edit';

    AnchorElement buttonCreate = AnchorElement();
    buttonCreate.classes = ['uk-button', 'uk-button-default', 'uk-button-small'];
    buttonCreate.href = '$protocol//$hostname:$port/add';
    buttonCreate.text = 'Create new post';
    cntnt?.children.add(buttonCreate);

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