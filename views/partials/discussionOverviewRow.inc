<?php foreach($model['discussions'] as $discussion): ?>
<div class="pure-g <?php self::htmlOut($model['cssGridClass']); ?>">
    <div class='pure-u-1 pure-u-md-1-2 '>
        <?php self::actionLink($discussion->getName(), 'Detail', 'Post', array(
                'did' => $discussion->getId()
            ));
        ?>
    </div>
    <div class='pure-u-1  pure-u-md-1-2 '>
        <i>(<?php self::htmlOut($discussion->getCreator()->getUsername()); ?> created @ <?php self::htmlOut($discussion->getCreationDate()); ?>) - <?php self::htmlOut($discussion->getNrOfPosts()); ?> Posts</i>
        <br>
        <?php if($discussion->getLastPost() !== null): ?>
        <i>last from:  <?php self::htmlOut(mb_strimwidth($discussion->getLastPost()->getCreator()->getUsername(), 0,19, "...")); ?> <?php self::htmlOut($discussion->getLastPost()->getCreationDate()); ?></i>
        <?php else: ?>
        <i>Noch kein Post :(</i>
        <?php endif; ?>
    </div>
</div>
<?php endforeach; ?>
