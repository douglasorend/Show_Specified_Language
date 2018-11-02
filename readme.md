-------

# SHOW SPECIFIED LANGUAGE v2.3

[**By Dougiefresh**](http://www.simplemachines.org/community/index.php?action=profile;u=253913) -> [Link to Mod](http://custom.simplemachines.org/mods/index.php?mod=4103)

-------

## Introduction
This mod allows a user to make a **SINGLE** post in multiple language and show ONLY the appropriate language to the visiting user.

For example, if a user posted this:
[quote]
[language=EN]Hi, this is a test[/language]
[language=EE]Tere, see on test[/language]
[language=ES]Hola, esta es una prueba[/language]
[language=english]Hi, this is a test[/language]
[language=estonian]Tere, see on test[/language]
[language=spanish]Hola, esta es una prueba[/language]
[/quote]
If the user had the Estonian language set, he/she would see this:
[quote]
Tere, see on test
Tere, see on test
[/quote]
Either the 2-character language code (ex: **en**) or the 5-character code (ex: **en-US**) may be used to specify the language to display.  Note that both **en-US** and **en-GB** will be matched to **en**, likewise for other languages.

If the user's language is not found, the code will either display the preferred default language (if specified) **OR** the first language code encountered.

## Admin Settings
Under **Admin** => **Forum** => **Posts and Topics** => **Post Settings**, there are 3 new settings:

- Allow sub-languages for **language** bbcode?
- Show only installed languages for **language** dropdown?
- Show natural language IDs instead of RFC-1766 IDs?

## Related Discussion

- [Support for multiple languages!](http://www.simplemachines.org/community/index.php?topic=544405.0)

## Translators

- Spanish Latin: [Rock Lee](https://www.simplemachines.org/community/index.php?action=profile;u=322597)

## Compatibility Notes
This mod was tested on SMF 2.0.11 and SMF 2.1 Beta 2, but should work on SMF 2.0 and up.  SMF 1.x is not and will not be supported.

## Changelog
The changelog can be viewed at [XPtsp.com](http://www.xptsp.com/board/free-modifications/show-specified-language/?tab=1).

## License
Copyright (c) 2016 - 2018, Douglas Orend

All rights reserved.

Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:

1. Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.

2. Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
