<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title><?php pageTitle(); ?> | <?php siteName(); ?></title>
	<?php editorScript(); ?>
	<link href="<?php editorStyle(); ?>" type="text/css" rel="stylesheet">
	<link href="<?php entityStyle(); ?>" type="text/css" rel="stylesheet">
	<script type="text/javascript" charset="utf-8" src="<?php utilsJs(); ?>"></script>
    <style type="text/css">
        .wrap { max-width: 720px; margin: 50px auto; padding: 30px 40px; text-align: center; box-shadow: 0 4px 25px -4px #9da5ab; }
        article { text-align: left; padding: 40px; line-height: 150%; }
    </style>
</head>
<body>
<div class="wrap">

    <header>
        <h2><?php siteName(); ?></h2>
        <nav class="menu">
            <?php navMenu(); ?>
        </nav>
    </header>

    <article>
        <!-- <h3><?php pageTitle(); ?></h3> -->
        <?php pageContent(); ?>
    </article>

	<script type="text/javascript" charset="utf-8" src="<?php editorJs(); ?>"></script>
	<script type="text/javascript" charset="utf-8" src="<?php entityJs(); ?>"></script>	
    <footer><small>&copy;<?php echo date('Y'); ?> <?php siteFoot(); ?>.<br><?php siteVersion(); ?></small></footer>

</div>
</body>
</html>