import 'dart:html';
import 'dart:convert';
import 'package:web_app/web/idb.dart';

// For reqests
final updateUrl = '/api/edit';
final deleteUrl = '/api/delete';
const headerName = 'App-Token';

var dataPointer = querySelector('form#post-elements') as FormElement;
var deleteForm = querySelector('from#delete-form') as FormElement;

final Element saveBtn = querySelector('#save-btn') as ButtonElement;
final Element deleteBtn = querySelector('#delete-btn') as ButtonElement;
// For message
final Element? msg = querySelector('#msg');
// For Indexed DB
const idbName = 'Blog';
const storeName = 'Token';

// Variable from any_page.dart
final Element? title = querySelector('title');
final Element? h1 = querySelector('h1');
final MetaElement description = querySelector('meta[name="description"]') as MetaElement;
final MetaElement keywords = querySelector('meta[name="keywords"]') as MetaElement;

final HiddenInputElement id = querySelector('input[name="id"]') as HiddenInputElement;
InputElement titleInput = querySelector('input[name="title"]') as InputElement;
InputElement descriptionInput = querySelector('input[name="description"]') as InputElement;
InputElement keywordsInput = querySelector('input[name="keywords"]') as InputElement;
CheckboxInputElement publish = querySelector('input[name="publish"]') as CheckboxInputElement;
TextAreaElement content = querySelector('textarea[name="content"]') as TextAreaElement;

Element? uri = querySelector('#uri');

var protocol = 'http';
var hostname = '';
var port = '8000';

void main(List<String> args) {
  makeRequest();
  saveBtn.onClick.listen(update);
  deleteBtn.onClick.listen(delete);
}

// Show edit page
Future<void> makeRequest() async {

  final uriString = uri?.text ?? '';
  uri?.remove();

  protocol = window.location.protocol;
  hostname = window.location.hostname ?? 'localhost';
  port = window.location.port;
  final path = (port != '') ? '$protocol//$hostname:$port/api/$uriString' 
                            : '$protocol//$hostname/api/$uriString';
  var token = await getToken();

  final httpRequest = HttpRequest();
  httpRequest.open('GET', path);
  httpRequest.setRequestHeader(headerName, token);
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
  msg?.text = 'Request failed, status=${request.status}';
}

void processResponse(String jsonString) {

  var jsonData = json.decode(jsonString);
  var itemJsonData = jsonData[0];

  if (itemJsonData.containsKey('error')) {
    h1?.text = 'Error';
    msg?.text = itemJsonData['error'];
    return;
  }

  if (!itemJsonData.containsKey('token-is-valid')) {
    window.open('/auth', '_parent');
    return;
  }
  
  fillPage(jsonData);
}

void fillPage(List<dynamic> jsonData) {
  
  var itemJsonData = jsonData[0];
  title?.text = 'Рeдактировать пост';
  h1?.text = itemJsonData['title'];
  
  titleInput.value = itemJsonData['title'];
  keywordsInput.value = itemJsonData['keywords'];
  descriptionInput.value = itemJsonData['description'];
  content.text = itemJsonData['content'];

  if (itemJsonData['publish'] == '1') {
    publish.checked = true;
  } else {
    publish.checked = false;
  } 
  
}

// Submit edit form
Future<void> update(Event e) async {
  e.preventDefault();
  var data = FormData(dataPointer);
  var token = await getToken();
  var header = {headerName: '$token'};

  HttpRequest.request(
    updateUrl, 
    method: 'POST', 
    requestHeaders: header, 
    sendData: data)
    .then((HttpRequest query) {
      if (query.status == 200) {
        final jsonString = query.responseText;
        if (jsonString != null) {
          processUpdate(jsonString);
          return;
        }
      }
      // The request failed. Handle the error.
      msg?.text = 'Request failed, status=${query.status}';
    });
}

void processUpdate(String jsonString) {

  var jsonData = json.decode(jsonString);
  var itemJsonData = jsonData[0];

  if (itemJsonData.containsKey('error')) {
    msg?.text = itemJsonData['error'];
    return;
  }

  if (itemJsonData.containsKey('success')) {
    window.open('/edit/0', '_parent');
    return;
  }
}

// Submit delete form
Future<void> delete(Event e) async {
  e.preventDefault();
  var data = FormData(dataPointer);
  var token = await getToken();
  var header = {headerName: '$token'};

  HttpRequest.request(
    deleteUrl, 
    method: 'POST', 
    requestHeaders: header, 
    sendData: data)
    .then((HttpRequest query) {
      if (query.status == 200) {
        final jsonString = query.responseText;
        if (jsonString != null) {
          processUpdate(jsonString); // processing similarly update
          return;
        }
      }
      // The request failed. Handle the error.
      msg?.text = 'Request failed, status=${query.status}';
    });
}
// Common function
dynamic getToken() async {
  Idb idb = Idb(idbName, storeName);
  var db = await idb.openDB(1);
  var records = await idb.cursor(db);
  var token = records[0]['token'];
  return token;
}

// import 'package:web_app/web/edit_page.dart' as fix;

// void main() {
//   fix.EditPage edit = fix.EditPage();
//   edit.makeRequest();
// }