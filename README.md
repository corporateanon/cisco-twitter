# Twitter XML service for Cisco IP Phone

Create Twitter app in https://apps.twitter.com/.

Grant it write permission.

## Testing

Create `.env` file in the project root and fill in the keys from your Twitter app:

```
TWITTER_CONSUMER_KEY="paste-here"
TWITTER_CONSUMER_SECRET="paste-here"
TWITTER_ACCESS_TOKEN="paste-here"
TWITTER_ACCESS_TOKEN_SECRET="paste-here"
```

`cd` to your project directory and run the following from the command line:

```
./debug
```

Open the service at http://your.server.ip.address:8080/ from your phone.

## Deploying to Heroku

You don't have to make `.env` file. Just `git push` your code to Heroku and set the corresponding environment variables in the app management console (like you did it in `.env` file)

**Attention! There is no authentication mechanism implemented. So deploying the app globally is a security violation!**

## Deploying to Apache

TODO
