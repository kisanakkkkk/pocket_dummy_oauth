# pocket_dummy_oauth
pocket dummy oauth to demonstrate token stealing poc with other app's token

inspired from https://salt.security/blog/oh-auth-abusing-oauth-to-take-over-millions-of-accounts
![image](https://github.com/kisanakkkkk/pocket_dummy_oauth/assets/70153248/2d78a8ca-7c7e-4a3e-8350-c0030647ca17)

![imagexx](https://github.com/kisanakkkkk/pocket_dummy_oauth/assets/70153248/3e59604f-51c2-4f93-a678-58fc9a5dc11f)


```
==setup==
1. sudo apt-get install php-curl
2. install composer
3. `sudo apt-get install php-mbstring`
4. composer init
5. add `"facebook/graph-sdk" : "~5.0"` on "require{}"
6. composer install

==Facebook app id & app secret==
1. create a facebook developer account (https://developers.facebook.com/docs/development/register/)
2. create an app: type->consumer, appname->bebas
3. add product: facebook login->web
4. app settings (left sidebar)-> basic
5. save app id and app secret, paste it to config.conf
6. scroll down, put http://localhost:8000/ in website url

==Github client id & client secret==
1. create new oauth app (https://github.com/settings/developers)
2. put http://localhost:8000/ in homepage url
3. put http://localhost:8000/github-callback.php in authorization callback url
4. save client id and client secret, paste it to config.conf

==deployment==
1. php -S 0.0.0.0:8000, go to http://localhost:8000/ on browser
2. log in with same account used as developer account
3. see access code and access token after redirected to profile.php 
```


check your access token here:
[facebook] : https://developers.facebook.com/tools/debug/accesstoken/

DISCLAIMER
Designed for use in a restricted local environment exclusively. Avoid deploying this on public services as the code remains highly susceptible to vulnerabilities.