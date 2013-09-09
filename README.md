DevShortcutsBundle
==================

DevShortcutsBundle is a symfony2 bundle that allows you to run often used console commands with shortcuts.
During development you always need to clear caches, reset the database, dump/watch the assets etc.
With the DevShortcutsBundle you can easily run this commands faster. 

E.g. the normal command to watch the assets
```
php app/console assetic:dump --watch
```
Same command with DevShortcutsBundle would be:
```
php app/console dev aw
or
sf dev aw
```

```sf``` is an alias for ```php app/console```. This alias needs to be configured by you. If you are using a linux distribution just edit .bash_aliases in you home folder or create it if it doesn't exist.
You only need to add a line as the following:
```
alias sf="php app/console"
```
Or if you want the alias to point to a specific project:
```
alias sf="php /path/to/your/symfony-project/app/console"
```

For Windows it's different. Plain DOS doesn't have support for aliases. You can use 4dos / 4nt, Cygwin or something similar to setup aliases.

The full list of Shortcuts you can use:

Shortcut | Original command | Usage
------------ |-------------| -----
cc | cache:clear | Clear cache 
ai | assets:install | Install assets
ad | assetic:dump | Dump assets
aw | assetic:dump --watch |  Watch assets
a | Install & dump assets (ai + ad combination)
dd | doctrine:database:drop --force | Drop database
dc | doctrine:database:create | Create database
sd | doctrine:schema:drop --force | Drop database schema
sc | doctrine:schema:create | Create database schema
dform | doctrine:fixtures:load --fixtures=./src/BundlePath/DataFixtures/ORM | Load ORM DataFixtures
dfenv | doctrine:fixtures:load --append --fixtures=./src/BundleName/DataFixtures/dev | Load environment related DataFixtures
d | Reloads the database: Drop database scheme, create scheme, load orm + env data fixtures (sd, sc, dform, dfenv shortcut combination)

You can also use ```sf dev --help``` to get the full list of commands.
