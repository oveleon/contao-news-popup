# Contao News Popup
This extension for the open source CMS Contao displays the last news in a popup. If the message was read or manually closed, the popup is not displayed until a new news item is available.

## Install
```shell
$ composer install oveleon/contao-news-popup
```

## Configure
1. Create front end module `Newspopup`
2. Bind module to a layout
3. Create a news item and allow it to be displayed in the popup (Select `Popup` checkbox)

## Contribute
1. `$ npm install`
2.  Apply changes
3. `$ npm run build`
4. Create PR