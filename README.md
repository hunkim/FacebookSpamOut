## Configuration

### Edit config.php (See config.php.sample)

* Get Facebook temp token at https://developers.facebook.com/tools/explorer/

Permissions:
   * To delete a user's post, a user access token with publish_actions permission is required.
   * To delete a Page's post a Page access token and publish_pages permission is required.
   * To delete a User's post on Page a Page access token is required.

* Extend Access Token at:
    - https://developers.facebook.com/tools/accesstoken/

* Add your group numeric ID. If you don't know, use this service:
    - http://findmyfbid.com/

## Run main.php
* Run main.php prediocally. (At least every hour.)
* Example (every 5 min):
  */5 * * * * cd /home/ubuntu/FacebookSpamOut/; php main.php
* You can run with group id:
** php main.php 174499879257223
* Also you can set the time interval
** php main.php 174499879257223 "-5 minutes"

