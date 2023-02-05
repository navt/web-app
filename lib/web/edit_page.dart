import 'dart:html';
import 'package:web_app/web/any_page.dart';

class EditPage extends AnyPage {
  
  final HiddenInputElement id = querySelector('input[name="id"]') as HiddenInputElement;
  InputElement titleInput = querySelector('input[name="title"]') as InputElement;
  InputElement descriptionInput = querySelector('input[name="description"]') as InputElement;
  InputElement keywordsInput = querySelector('input[name="keywords"]') as InputElement;
  CheckboxInputElement publish = querySelector('input[name="publish"]') as CheckboxInputElement;
  TextAreaElement content = querySelector('textarea[name="content"]') as TextAreaElement;
  
  @override
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
}