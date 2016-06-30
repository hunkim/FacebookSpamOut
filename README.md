## Configuration

### Edit config.php (See config.php.sample)

Get Facebook temp token at:
    - https://developers.facebook.com/tools/explorer/

 Permissions:
   * To delete a user's post, a user access token with publish_actions permission is required.
   * To delete a Page's post a Page access token and publish_pages permission is required.
   * To delete a User's post on Page a Page access token is required.

Extend Access Token at:
    - https://developers.facebook.com/tools/accesstoken/

Add your group id

## Run main.php
Run main.php prediocally. (At least every hour.)

*/5 * * * * cd /home/ubuntu/FacebookSpamOut/; php main.php
