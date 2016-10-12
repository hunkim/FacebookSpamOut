## Configuration

### Edit config.php (See config.php.sample)

#### Facebook
* Get a Facebook temp token at https://developers.facebook.com/tools/explorer/

* Required permissions:
   - To delete a user's post, a user access token with publish_actions permission is required.
   - To delete a Page's post a Page access token and publish_pages permission is required.
   - To delete a User's post on Page a Page access token is required.

* Extend the Access Token at:
    - https://developers.facebook.com/tools/accesstoken/

* Add your group/page numeric ID. If you don't know the ID, use this service:
    - http://findmyfbid.com/

#### Google Vision API
Get an API Key. See https://cloud.google.com/vision/docs/common/auth#set_up_an_api_key.

## Module (configuration) testing
Make sure each module is working OK.

### Facebook
```bash
php FBUtil.php
```

### Google Vision
```bash
php gVisionAPI.php "http://home.cse.ust.hk/~hunkim/images/Sungkim2.png"
```

You will see something like this:
```json
Checking http://home.cse.ust.hk/~hunkim/images/Sungkim2.png
{
  "responses": [
    {
      "safeSearchAnnotation": {
        "adult": "VERY_UNLIKELY",
        "spoof": "VERY_UNLIKELY",
        "medical": "VERY_UNLIKELY",
        "violence": "VERY_UNLIKELY"
      }
    }
  ]
}
```

## Run main.php periodically.
* crontab Example (every 5 min):
```
*/5 * * * * cd /home/ubuntu/FacebookSpamOut/; php main.php
```
* You can run with group id.
```bash
php main.php 174499879257223
```
* Also you can set the time interval. Sync with your cron interval.
```bash
php main.php 174499879257223 "-5 minutes"
```
## Contribution

We always welcome your comments and PRs!
