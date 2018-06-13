# ip_region
Ip gets real address, data source Taobao API build.


```php
<?php

require __DIR__.'/vendor/autoload.php';


print_r(
	Ofcold\IPRegion\Region::make('113.88.160.22')
);
```