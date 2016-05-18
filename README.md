# Phabricator SparkPost

[![Software License][ico-license]](LICENSE.md)
[![Quality Score][ico-code-quality]][link-code-quality]

Phabricator Sparkpost is a mail adapter for [Phabricator](https://phabricator.org) and enables the usage of [SparkPost](https://sparkpost.com) for sending
outbound email.

> **Note**
>
> The Phabricator upstream does officially not offer support with extension development. This means that upgrading your Phabricator instance
> while running this library can cause trouble because of upstream API changes. **Always test your Phabricator instance after upgrading while
> using this library to ensure it still works like a new Tesla.** If it does not work as expected, please open a [new issue](https://github.com/hiddeco/phabricator-sparkpost/issues/new).


## Installation

Installation of Phabricator SparkPost is fairly easy because it is build as a [libphutil](https://github.com/phacility/libphutil) library.

1.	`git clone` Phabricator SparkPost next to your `arcanist`, `libphutil` and `phabricator` folders.

	```bash
	root@box:/$ ls -l
    drwxr-xr-x  8 root root 4096 May 17 18:20 arcanist
    drwxr-xr-x  9 root root 4096 May 17 18:20 libphutil
    drwxr-xr-x 11 root root 4096 May 18 06:05 phabricator
    root@box:/$ git clone https://github.com/hiddeco/phabricator-sparkpost.git
    ```

2.	Link `phabricator-sparkpost` with your Phabricator instance. You do this by adding `phabricator-sparkpost` to your `load-libraries` config key. E.g.

	```bash
	root@box:phabricator/$ ./bin/config set load-libraries '{"phabricator-sparkpost": "phabricator-sparkpost\/src\/"}'
	```

3.	Set your `sparkpost.api-key`.
	_Note: this API key needs Transmissions: Read/Write permissions._

	```bash
	root@box:phabricator/$ ./bin/config set sparkpost.api-key <key>
	```

4.	Configure Phabricator to use `PhabricatorMailImplementationSparkPostAdapter` as the mail adapter by setting `metamta.mail-adapter`

	```bash
	root@box:phabricator/$ ./bin/config set metamta.mail-adapter PhabricatorMailImplementationSparkPostAdapter
	```

5.	Test sending email.

	```bash
	root@box:phabricator/$ ./bin/mail send-test
	```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email hello@hidde.co instead of using the issue tracker.

## Credits

- [Hidde Beydals][link-author]
- [All Contributors][link-contributors]

## License

Licensed under the Apache License, Version 2.0. Please see [License](LICENSE.md) for more information.

[ico-license]: https://img.shields.io/badge/license-APLv2-brightgreen.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/hiddeco/phabricator-sparkpost.svg?style=flat-square

[link-code-quality]: https://scrutinizer-ci.com/g/hiddeco/phabricator-sparkpost
[link-author]: https://github.com/hiddeco
[link-contributors]: ../../contributors
