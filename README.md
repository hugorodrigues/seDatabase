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


### update($table, $data, $where)
```php
$db->update('books', array('title'=>'New title!'), 1);
// Executes: update books set title = 'New title' where id = 1;

$db->update('books', array('title'=>'New title'), array('year > 2012'));
// Executes: update books set title = 'New title' where year > 2012;

// Escaped 'where params' when you get them from your users
$db->update('books', array('title'=>'New title'), array('year > :year', array(':year'=>2012)));
// Executes: update books set title = 'New title' where year > 2012;
```



### delete($table, $where)
```php
$db->delete('books', 1);
// delete from books where id = 1;

$db->delete('books', array('year > 2012'));
// delete from books where year > 2012;

$db->delete('books', array('year > :year', array(':year'=> 2012)));
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
$db->getRow('select * from books where year > :year', array(':year'=>2012)); 
// array(
// 	0 => array('id'=> 1, 'title'=>'New title', 'isbn'=>'AB123', 'year'=>'2012'),
// 	1 => array('id'=> 2, 'title'=>'New title 2', 'isbn'=>'AB124', 'year'=>'2012'),
)
```


---


## Abstract

### query($sql, $binds)
```php
$db->query('truncate books');
```













