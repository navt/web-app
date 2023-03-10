import 'dart:html';
import 'dart:convert';
import 'package:web_app/web/idb.dart';

// For reqest
final url = '/api/auth';
const headerName = 'App-Token';
var dataPointer = querySelector('form#lform') as FormElement;
final Element button = querySelector('#btn') as ButtonElement;
// For message
final Element? msg = querySelector('#msg');
// For Indexed DB
const idbName = 'Blog';
const storeName = 'Token';


void main(List<String> args) {
  button.onClick.listen(click);
}

Future<void> click(Event e) async {
  e.preventDefault();

  // https://api.dart.dev/stable/2.5.0/dart-html/HttpRequest/request.html
  var data = FormData(dataPointer);
  var token = await getToken();
  var header = {headerName: '$token'};

  HttpRequest.request(
    url, 
    method: 'POST', 
    requestHeaders: header, 
    sendData: data)
    .then((HttpRequest query) {
      if (query.status == 200) {
        final jsonString = query.responseText;
        if (jsonString != null) {
          processResponse(jsonString);
          return;
        }
      }
      // The request failed. Handle the error.
      msg?.text = 'Request failed, status=${query.status}';
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
    // Change token
    Idb idb = Idb(idbName, storeName);
    var db = await idb.openDB(1);
    var records = await idb.cursor(db);
    var old = records[0];  
    idb.del(db, old['created']);
    idb.add(db, itemJsonData['newtoken']);

    window.open('/edit/0', '_parent');
  }
}

dynamic getToken()async {
  Idb idb = Idb(idbName, storeName);
  var db = await idb.openDB(1);
  var records = await idb.cursor(db);
  var token = records[0]['token'];
  return token;
}