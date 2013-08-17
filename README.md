PHP-Premailer
=============

While developing some emails for my recent project, [Podium](http://hellopodium.com/), I stumbled on [alexdunae](https://github.com/alexdunae)'s wonderful tool [Premailer](http://premailer.dialect.ca/). This tool allows you copy/paste markup with `style` tags, and have inline-styled code returned back.

I looked around for some libraries to do that programatically in PHP, finding two. [CssToInlineStyles](https://github.com/tijsverkoyen/CssToInlineStyles) and [InlineStyle](https://github.com/christiaan/InlineStyle). Neither of these worked for my situation, so I decided to provide a PHP proxy/wrapper for [alexdunae](https://github.com/alexdunae/premailer)'s open source version (accessible here: <https://github.com/alexdunae/premailer>).

Here it is.  
I hope you find it useful.

### Example

``` php
<?php

    // Source
    require_once APP . '/vendors/PHP-Premailer/Premailer.class.php';

    // Pass in the html markup (which will have style blocks in it)
    $premailer = (new Premailer($body));    
    $body = $premailer->getConvertedHtml();
    
```

That's it.  
The library will generate the proper `bash` code to call the `ruby` library.  
See the <https://github.com/onassar/PHP-Premailer/blob/master/Premailer.class.php#L22> for the options the PHP library supports (almost all of them).

If you have any feedback, please reach out: <onassar@gmail.com>