# MuteAll

[![](https://poggit.pmmp.io/shield.state/MuteAll)](https://poggit.pmmp.io/p/MuteAll)
[![](https://poggit.pmmp.io/shield.dl.total/MuteAll)](https://poggit.pmmp.io/p/MuteAll)

A PocketMine-MP plugin to mute all players on the server.

# Features

- Permission bypass.
- Supports `&` as formatting codes.
- Custom messages.
- Lightweight and open source ❤️

# Default Config
```yaml
---
---
# Do not change this (Only for internal use)!
config-version: 1.1

# Messages are used when muting player chats.
# Use "§" or "&" to color the message.
message: "&cYou can't chat when global mute is enabled"

# Turn on mute
turn-on: "&aTurn on mute chat for all players"
# Turn off mute
turn-off: "&aTurn on mute chat for all players"
...

```

# For Developers

We provide API for developers to write addons/plugins that depends with MuteAll.
To access MuteAll API class, you can use `aiptu\muteall\MuteAll::getInstance()`.

```php
- Check whether global mute is enabled or not:

MuteAll::getInstance()->isMuteAll();

- Set global mute to turn on or off:

MuteAll::getInstance()->setMuteAll(bool $value);
```

# Upcoming Features

- Currently none planned. You can contribute or suggest for new features.

# Additional Notes

- If you find bugs or want to give suggestions, please visit [here](https://github.com/AIPTU/MuteAll/issues).
- We accept all contributions! If you want to contribute, please make a pull request in [here](https://github.com/AIPTU/MuteAll/pulls).
- Icons made from [www.flaticon.com](https://www.flaticon.com)
