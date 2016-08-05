# apr-php

**For educational purposes only**

Neoficijalni PHP library za pretragu APRa.

## Example

```php
<?php
include("./apr.php");

$apr =  new Apr();

print_r($apr->search("sbb", 1));
```

## search

Prvi argument je ime firme

Drugi argument je kategorija. 

Kategorije: 

```
1 привредних друштава
2 предузетника
3 удружења
4 стeчајних маса
5 фондација и задужбина
6 спортских удружења
7 привредних комора и представништава
```

## napomena

Da skripta ne bi svaki put tražila CSRF token i cookie od APRa, preporučujem da sačuvate te podatke negde i inicijalizujete klasu sa njima.

To bi izgledalo ovako: 

```php
<?php
include("./apr.php");

$apr =  new Apr();

$apr1 = new Apr($apr->getCookie(), $apr->getHidden(), $apr->getServer());
```
Najbolje bi bilo da posle svakog pokretanja sačuvate ta 3 parametra, bez obzira na to da li je pokrenuta bez njih ili ne. 

Ako neki od tih tokena istekne, skripta će detektovati error i zameniti ga. 
