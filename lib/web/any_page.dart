import 'dart:html';
import 'dart:convert';

class AnyPage {
  
  final Element? title = querySelector('title');
  final Element? h1 = querySelector('h1');
  final MetaElement description = querySelector('meta[name="description"]') as MetaElement;
  final MetaElement keywords = querySelector('meta[name="keywords"]') as MetaElement;
  final Element? cntnt = querySelector('#cntnt');

  Element? uri = querySelector('#uri');

  var protocol = 'http';
  var hostname = '';
  var port = '8000';

  Future<void> makeRequest() async {

    final uriString = uri?.text ?? '';
    uri?.remove();

    protocol = window.location.protocol;
    hostname = window.location.hostname ?? 'localhost';
    port = window.location.port;
    final path = (port != '') ? '$protocol//$hostname:$port/api/$uriString' 
                              : '$protocol//$hostname/api/$uriString';

    final httpRequest = HttpRequest();
    httpRequest.open('GET', path);
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
    var itemJsonData = jsonData[0];

    if (itemJsonData.containsKey('error')) {
      h1?.text = 'Error';
      cntnt?.text = itemJsonData['error'];
      return;
    }
    
    fillPage(jsonData);
  }

  void fillPage(List<dynamic> jsonData) {
    var itemJsonData = jsonData[0];

    keywords.content = itemJsonData['keywords'];
    description.content = itemJsonData['description'];
    title?.text = itemJsonData['title'];
    h1?.text = itemJsonData['title'];
    cntnt?.innerText = itemJsonData['content'];
  }
}