<?php
defined('_JEXEC') or die;

?>
<div id="bar" style="display:none;">
<?php
foreach ($this->btns as $b)
{
?>
	<div class="bar-btn" style="margin:0;padding:5px;cursor:pointer;">
		<img src="<?php echo $b['src']; ?>" alt="<?php echo $b['alt']; ?>" />
	</div>
<?php
}
?>
	<div class="bar-btn" style="margin:0;padding:5px;cursor:pointer;">...</div>
</div>