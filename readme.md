# Mikrotik Api for Laravel 5.4+

Instalation
----

Via composer:
```
composer require jjsquady/mikrotikapi
```

Or manually insert this block into your composer.json in require section:
```
"require": {
    "jjsquady/mikrotikapi": "dev-master", // <- this line
}
```

Configuration on Laravel:
----

Insert into `app/config.php` in `providers` array:

```
jjsquady\MikrotikApi\MikrotikServiceProvider::class
```

#### Use the Facade:

Insert into `app/config.php` in `facades` array:

```
'Mikrokit' => jjsquady\MikrotikApi\Facades\MikrotikFacade::class
```

Basic Usage:
----

```$php

// create a connection with Mikrotik Router

$conn = Mikrokit::connect(['<host_ip>', '<username', '<password>']);
 
if($conn->isConnected()) {
    // you have access to Commands
    // and can call from here...
}
```

Getting interfaces:
---
```$php
$conn = Mikrokit::connect(['<host_ip>', '<username', '<password>']);
 
if($conn->isConnected()) {
    $iComm = new InterfaceCommand($conn);
    $interfaces = $iComm->all() // returns all interfaces as array
    //
    $interfaces = $iComm->get() // returns all interfaces as InterfaceEntity Object
    
    // you can send it to view 
    return view("<some_view>", [
        'interfaces' => $interfaces
    ]);
}
```



##### Created by jjsquady (Jorge Junior)
##### (cc) 2017
##### License: MIT