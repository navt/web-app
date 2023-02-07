import 'dart:html';
import 'dart:indexed_db';

class Idb {
  late String dbName;
  late String storeName;
  late Database db;
  
  Idb(this.dbName, this.storeName);
  
  Future<Database> openDB(int version) {
    return window.indexedDB!.open(dbName, version: version,
      onUpgradeNeeded: (e) {
        Database db = e.target.result;
        if (!db.objectStoreNames!.contains(storeName)) {  
          db.createObjectStore(storeName, keyPath: "created");
          print('Created store $storeName');
        }
      });
  }

  void add (Database db, dynamic value) async {
    var txn = db.transaction(storeName, "readwrite");
    var store = txn.objectStore(storeName);
    var trgt = store.add(value);
    await txn.completed;
    trgt.then((value) => print('$value added')); // print key
  }

  Future<List> cursor(Database db) async {
    var txn = db.transaction(storeName, "readonly");
    var store = txn.objectStore(storeName);
    List<dynamic> records = [];

    var cursors = store.openCursor().listen(
      (cursor) {
        // ...some processing with the cursor
        records.add(cursor.value);
        cursor.next(); // advance onto the next cursor.
      },
      onDone: () {
        // called when there are no more cursors.
        print('all done! cursor');
      });
    await txn.completed;

    if (records.isEmpty) {
      add(db, {"created": 111, "token": "222.333.444"});
      return [{"created": 111, "token": "222.333.444"}];
    }
    
    return records;
  }

  void del(Database db, dynamic key) async {
    var txn = db.transaction(storeName, "readwrite");
    var store = txn.objectStore(storeName);
    store.delete(key);
    await txn.completed;
    print('$key deleted'); 
  }  
}