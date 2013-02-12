Malmo Yii
========

Malmo is simple Yii superstructure. Main idea of Malmo is platform that includes
often used components, functions and extensions, than Yii doesn't has.
Malmo is great for middle size system - from simple e-shops to complex search and aggregator systems.

With Malmo you get application skeleton, ready for development without any additional configuration.

[![Build Status](https://travis-ci.org/sergebezborodov/malmo.png?branch=master)](https://travis-ci.org/sergebezborodov/malmo)

Whats included:
--------------
- Gearman worker application for fast start with gearman service.
- Database extends:
	* DbConnection emulate nested transaction for MySql and other DB that doesn't support it
	* DbCommand with feature addWhere and queryAssoc
	* Base ActiveRecord class. Implement often used functions - automatic sets dates fields in record, sets null value instead of empty string
- Power MultiSite component for working with multisites
- Cookie Manager component for organaize your project cookies in one config
- Base Controller class with some helpful methods
- Tag Caching behaviour, enabled by default
- Unit tests for all internal components

Does Malmo broke my exists project?
---------------------------------
NO, and one more NO. Malmo doesn't change any exists Yii Api, only extends it.


How to install
--------------
- Download Malmo zip
- Extract to your application directory
- In app index.php (and console script) define ROOT constant with application root directory.
   Replace include yii.php with malmo/malmo.php
- All done



History:
---------------
I stared develop with Yii in 2009 year and, as many developers do, for every project I copy-paste some classes.
After some time I decided organaise all of my shared code in one place. I created base project template,
but with each new version I improved functional and refactored old shared code,
and main problem - I didn't update old project with new.
After all I decided create shared platform under Yii Framework.
