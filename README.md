# pocket_dummy_oauth
pocket dummy oauth to demonstrate token stealing poc with other app's token

inspired from https://salt.security/blog/oh-auth-abusing-oauth-to-take-over-millions-of-accounts

![imagexx](https://github.com/kisanakkkkk/pocket_dummy_oauth/assets/70153248/3e59604f-51c2-4f93-a678-58fc9a5dc11f)


```
==setup==
1. install composer
2. `sudo apt-get install php-mbstring`
3. composer init
4. add `"facebook/graph-sdk" : "~5.0"` on "require{}"
5. composer install

==app id & app secret==
1. create a facebook developer account (https://developers.facebook.com/docs/development/register/)
2. create an app: type->consumer, appname->bebas
3. add product: facebook login->web
4. app settings (left sidebar)-> basic
5. save app id and app secret, paste it to config.conf
6. scroll down, put http://localhost:8000/ in website url

==deployment==
1. php -S 0.0.0.0:8000, go to http://localhost:8000/ on browser
2. log in with same facebook account used as developer account
3. see access code and access token after redirected to profile.php 
```

check your access token here: https://developers.facebook.com/tools/debug/accesstoken/
