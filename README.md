# seDatabase
Dead simple database (pdo) access.

* Simple - Less than 130 Lines of code
* Fast - No bloated old ORM code
* Safe - Prepared statements everywhere, goodbye sqlInjections


## Define
```php
$db = new seDatabase(array(
	'dsn' => 'mysql:host=localhost;dbname=test',
	'user' => 'root',
	'password' => 'l33tpassword',
	//'options' => array(),
	//'attributes' => array(),
));
```

---

## CHANGE data (crud) 

### insert($table, $data)
```php
$insertId = $db->insert('books',array(
	'title'=>'Cool book',
	'isbn'=>'AB123',
	'year'=>2012,
));

//Executes: insert into books (title, isbn) values ('Cool book', 'AB123');
```


### update($table, $data, $where, $binds)
```php
$db->update('books', array('title'=>'New title!'), 1);
// Executes: update books set title = 'New title' where id = 1;

$db->update('books', array('title'=>'New title'), 'year > 2012');
// Executes: update books set title = 'New title' where year > 2012;

// Escaped 'where params' when you get them from your users
$db->update('books', array('title'=>'New title'), 'year > :year', array(':year'=>2012));
// Executes: update books set title = 'New title' where year > 2012;
```



### delete($table, $where, $binds)
```php
$db->delete('books', 1);
// delete from books where id = 1;

$db->delete('books', 'year > 2012');
// delete from books where year > 2012;

$db->delete('books', 'year > :year', array(':year'=> 2012));
// delete from books where year > 2012;
```


---


## FETCH Data

### getVar($sql, $params)
```php
$result = $db->getVar('select title from books where id = :id', array(':id'=>1));
// 'New title'
```


### getRow($sql, $params)
```php
$result = $db->getRow('select * from books where id = :id', array(':id'=>1));
// array('id'=> 1, 'title'=>'New title', 'isbn'=>'AB123', 'year'=>'2012')
```

### getResults($sql, $params)
```php
$db->getResults('select * from books where year > :year', array(':year'=>2012)); 
// array(
// 	0 => array('id'=> 1, 'title'=>'New title', 'isbn'=>'AB123', 'year'=>'2012'),
// 	1 => array('id'=> 2, 'title'=>'New title 2', 'isbn'=>'AB124', 'year'=>'2012'),
//)
```


---


## Abstract

### query($sql, $binds)
```php
$db->query('truncate books');
```





---
## License 

(The MIT License)

Copyright (c) 2012, Hugo Rodrigues - hugo AT starteffect DOT com

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
