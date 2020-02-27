theme: Franziska, 8

[.header: alignment(left), Courier]
## GET /HTTP-Caching-mit-Symfony
### Host: SymfonyUserGroup.koeln
### Date: Thu, 26 Feb 2020 18:05:00 GMT

```php
```
Matthias Pigulla \<mp@webfactory.de>
webfactory GmbH, Bonn

---

# [fit] 16 • 37 = ? 

---

# Wenn ich HTTP sprechen könnte… 🤓

```
GET /multipliziere?a=16&b=37 HTTP/1.1
Host: tas.chenrech.ner
```

```
-> https://tas.chenrech.ner/multipliziere?a=16&b=37 HTTP/1.1
```

--- 

# Lookup-Schlüssel 

```
[ 
    "GET",
    "https://tas.chenrech.ner/multipliziere?a=16&b=37",
    "..." // erstmal egal   
]
```

---

# Zwischenspeicherbare HTTP-Verben

(RFC 7231 Abschnitt 4.2.3)

* GET
* HEAD
* POST (_muss_ aber ins Backend durchschreiben 🤕)

---

## Standardmäßig zwischenspeicherbare Status-Codes

(RFC 7231 Abschnitt 6.1)
  
* 200 OK
* 301 Moved permanently
* 404 Not found
* ...

---

[.background-color: #FFFFFF]
![fit](caches.jpg)


---

# Details

* RFC 7234 – HTTP/1.1: Caching
* RFC 7231 – HTTP/1.1: Semantics and Content
* RFC 7232 – HTTP/1.1: Conditional Requests

--- 

```php
```

# (Und nun: ein bisschen Live-Coding)

---

```php
```

# Vorsicht bei `public` 🤕

---

```php
```

# `Vary` Header 🏳️‍🌈 

---

```php
```

# [fit] Edge Side Includes (ESI)

---

[.background-color: #FFFFFF]
![fit](esi-cache.svg)
     
---

```php
```
# github.com/webfactory/
# symfony-http-caching-demo

---

```php

```
# [fit] Fragen

---

```php

```
# [fit] 🙏🏻 🍻 🚀

---

# Matthias Pigulla (`mpdude`)
## webfactory GmbH, Bonn – Germany
## mp@webfactory.de // @mpdude_de

```php
```

# github.com/webfactory/symfony-http-caching-demo
