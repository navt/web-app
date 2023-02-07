import 'dart:html';
import 'dart:convert';
import 'package:web_app/web/idb.dart';

final Element button = querySelector('#btn') as ButtonElement;
final Element? msg = querySelector('#msg');

var form = querySelector('#lform') as FormElement;

void main(List<String> args) {
  button.onClick.listen(click);
}

Future<void> click(Event e) async {
  e.preventDefault();

  // https://api.dart.dev/stable/2.19.1/dart-html/HttpRequest/postFormData.html
  var formData = FormData(form);
  var data = {'login':"${formData.get('login')}", 'password': "${formData.get('password')}"};
  var token = await getToken();
  var header = {'App-Token': '$token'};

  HttpRequest.postFormData('/api/auth', data, requestHeaders: header).then((HttpRequest resp) {
    var json = resp.responseText ?? '';

    if (json != '') {
      processResponse(json);
    }

  });
}

Future<void> processResponse(String jsonString) async {
  var jsonData = json.decode(jsonString);
  var itemJsonData = jsonData[0];

  if (itemJsonData.containsKey('error')) {
    msg?.text = itemJsonData['error'];
    return;
  }

  if (itemJsonData.containsKey('newtoken')) {
    // change token
    Idb idb = Idb('Blog', 'Token');
    var db = await idb.openDB(1);
    var records = await idb.cursor(db);
    var old = records[0];  
    idb.del(db, old['created']);
    idb.add(db, itemJsonData['newtoken']);

    window.open('/edit/0', '_parent');
  }
}

dynamic getToken()async {
  Idb idb = Idb('Blog', 'Token');
  var db = await idb.openDB(1);
  var records = await idb.cursor(db);
  var token = records[0]['token'];
  return token;
}