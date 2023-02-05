import 'package:web_app/web/any_page.dart' as any;

void main() {
  any.AnyPage one = any.AnyPage();
  one.makeRequest();
}
// $ dart compile js -O1 -o web/js/one.dart.js web/bin/one.dart
