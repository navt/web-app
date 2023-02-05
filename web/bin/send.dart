import 'dart:html';
import 'dart:convert';
import 'package:web_app/web/idb.dart';

/*
~/dart-apps/web-app$ dart compile js -O1 -o web/js/send.dart.js web/bin/send.dart

void main() async {

  Idb idb = Idb('Blog', 'Token');
  var db = await idb.openDB(1);

  idb.add(db, {"created": "1675204200", 
    "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6MSwiaWF0IjoxNjc1MjUzNDcwLCJleHAiOjE2NzUzODMwNzB9.f1462829d33eab4468117a4dde8a6730d96c61a0601202dda45ec3e4e914acc7"});
  
  var records = await idb.cursor(db);
  print(records);
  print(records[0]['created']);

  idb.del(db, records[0]['created']);
 
}
 */

var protocol = 'http';
var hostname = '';
var port = '8000';

final Element? cntnt = querySelector('#cntnt');
final Element? h1 = querySelector('h1');

void main() {
  makeRequest();
}

dynamic getToken()async {
  Idb idb = Idb('Blog', 'Token');
  var db = await idb.openDB(1);
  var records = await idb.cursor(db);
  var token = records[0]['token'];
  print(token);
  return token;
}

Future<void> makeRequest() async {
  final uri = 'accept.php';

  var token = await getToken();

  protocol = window.location.protocol;
  hostname = window.location.hostname ?? 'localhost';
  port = window.location.port;
  final path = (port != '')
      ? '$protocol//$hostname:$port/$uri'
      : '$protocol//$hostname/$uri';

  final httpRequest = HttpRequest();
  httpRequest.open('GET', path);
  httpRequest.setRequestHeader('App-Token', token);
  httpRequest.onLoadEnd.listen((e) => requestComplete(httpRequest));
  httpRequest.send('');
}

void requestComplete(HttpRequest request) {
  if (request.status == 200) {
    final response = request.responseText;
    if (response != null) {
      processResponse(response);
      return;
    }
  }
  // The GET request failed. Handle the error.
  cntnt?.text = 'Request failed, status=${request.status}';
}

void processResponse(String jsonString) {

  var jsonData = json.decode(jsonString);
  // print(jsonData.runtimeType);

  for(var item in jsonData.entries){
    // item представляет MapEntry<K, V>
    print("${item.key} : ${item.value}");
  }
}
