import 'dart:html';
import 'package:web_app/web/any_page.dart';

class AllPages extends AnyPage {
  
  final UListElement postsList = querySelector('#posts-list') as UListElement;
  
  @override
  void fillPage(List<dynamic> jsonData) {
  
    keywords.content = 'пост,обзор';
    description.content = 'Обзор постов блога';
    title?.text = 'Блог - Обзор постов';
    h1?.text = 'Список постов';

    for (var i = jsonData.length - 1; i >= 0; i--) {

      final li = LIElement();

      // https://api.dart.dev/stable/2.18.2/dart-html/HtmlElement-class.html
      AnchorElement a = AnchorElement();
      final id = jsonData[i]['id'];
      final title = jsonData[i]['title'];
      a.href = (port != '') ? '$protocol//$hostname:$port/posts/$id' 
                            : '$protocol//$hostname/posts/$id';
      a.text = '$title';
      li.children.add(a);

      HRElement date = HRElement();
      final buff = jsonData[i]['create_date'];
      final cd = buff?.substring(0, 16);
      date.text = '$cd';
      li.children.add(date);

      postsList.children.add(li);
    }
  }
}