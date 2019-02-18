# Chest

## When using only one connection and you need to get Chest instance with manual commit, use:
```
    $chest = Chest::getInstance(); // For getting Chest instance
    $chest->manualCommit();
    <do the database changes>
    $chest->commit(); // Commit the changes or
    $chest->rollback(); // Rollbacks the changes
```


## When using more than one connection, use:
```
    $chest = Chest::open(); // Saves the previous connection and gets a new instance
    $chest->manualCommit(); // Uses manual commit 
    <do the database changes>
    $chest->commit(); // Commit the changes or
    $chest->rollback(); // Rollbacks the changes
    Chest::close(); // Closes the current connection and reopens the previous connection
```
