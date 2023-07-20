# MuteAll

[![](https://poggit.pmmp.io/shield.state/MuteAll)](https://poggit.pmmp.io/p/MuteAll)
[![](https://poggit.pmmp.io/shield.dl.total/MuteAll)](https://poggit.pmmp.io/p/MuteAll)

A PocketMine-MP plugin to mute all players on the server.

# Features

- `Global Chat Mute`: Enable a global chat mute for all players on the server, preventing them from sending chat messages.
- `Bypass Permission`: Allow players with the `muteall.bypass` permission to chat even during global mute.
- `Customizable Messages`: Personalize messages displayed to players when they attempt to chat during global mute and when the mute is turned on or off.
- `Color Formatting`: The messages can be colored using "§" or "&" color codes, providing visual appeal and customization options for the messages.
- `Config Version Management`: The plugin ensures that the configuration file is up-to-date. If an outdated config file is provided, it generates a new one and backs up the old config, preventing compatibility issues.
- `User-friendly Command`: Use the simple `/muteall` command to toggle the global chat mute on or off easily.

# Default Config
```yaml
# Do not change this (Only for internal use)!
config-version: 1.2

# Messages Configuration
# These messages are used when muting player chats.
# You can use "§" or "&" to color the messages.

# The message displayed to players when they attempt to chat during global mute.
message: "&cYou can't chat when global mute is enabled"

# The message displayed to players when the global mute is turned on.
turn-on: "&aMute chat for all players has been turned on"

# The message displayed to players when the global mute is turned off.
turn-off: "&aMute chat for all players has been turned off"

```

# Upcoming Features

- Currently none planned. You can contribute or suggest for new features.

# Additional Notes

- If you find bugs or want to give suggestions, please visit [here](https://github.com/AIPTU/MuteAll/issues).
- We accept all contributions! If you want to contribute, please make a pull request in [here](https://github.com/AIPTU/MuteAll/pulls).
- Icons made from [www.flaticon.com](https://www.flaticon.com)
