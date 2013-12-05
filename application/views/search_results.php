<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">

<html lang="en">
<head>
	
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Search</title>
	
	<style type="text/css">
		body { font: 95% "Lucida Grande", "Trebuchet MS", Verdana, sans-serif;}
		.hl { font-weight: bold; }
		ul { list-style: none; margin: 0; padding: 0; }
		li { margin: 5px 0; width: 600px; }
                .date { color: green;}
form { background: url(http://inet-sochi.ru/tdbgg.png) #f4f4f4; max-width: 50em; }
	</style>
	
</head>
<body>

<?php $this->load->helper(array('form', 'search')); ?>

<?php echo form_open('/'); ?>
<?php echo form_label('Search:', 'search-box'); ?>
<?php echo form_input(array('name' => 'q', 'id' => 'search-box', 'value' => $search_terms)); ?>
<?php echo form_submit('search', 'Search'); ?>
<?php echo form_close(); ?>

<?php if ( ! is_null($results)): ?>
	<?php if (count($results)): ?>
		
		<p>Showing search results for '<?php echo $search_terms; ?>' (<?php echo $first_result; ?>&ndash;<?php echo $last_result; ?> of <?php echo $total_results; ?>):</p>
		
		<ul>
		<?php foreach ($results as $result): ?>
		<li><a href="<?php echo $result->url; ?>"><?php echo search_highlight($result->title, $search_terms); ?></a><br /><?php echo search_extract($result->content, $search_terms); ?><br><span class="date"><?php echo $result->date; ?></span></li>
		<?php endforeach ?>
		</ul>
		
		<?php echo $this->pagination->create_links(); ?>
		
	<?php else: ?>
		<p><em>There are no results for your query.</em></p>
	<?php endif ?>
<?php endif ?>

<?php if (isset($search_time)): ?>
	<p>Search time: <?php echo $search_time; ?></p>
<?php endif ?>

</body>
</html>