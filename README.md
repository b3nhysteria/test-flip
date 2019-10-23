env : php v7 and mysql
for start :
1.  change first the value configuration databases in configuration/env.php
2.  run the script for migration
    with php sql-command/migration.php
3.  up the server using :
    example : php -S localhost:9000

for running the application
there is collection request and env in postman
if you not familiar with postman, please chat me, so i'll add instruction with more detail 

on the environtment file postman [flip-dev.postman_environment.json], please change the value host api with name of that you already use on point 3 before.

my list api :

- add merchant => will add merchant + accoun finance
- add balance merchant => for adding balance merchant
- merchant withdraw => for request withdraw (get request id from herek, please put it on status withdraw)
- status withdraw => for checking is the request withdraw is out or not yet
- bank list => for get list of bank that system have
- list merchant => for get info merchant with pagination
- get finance account merchant => for get info bank account bank code, for withdraw request
