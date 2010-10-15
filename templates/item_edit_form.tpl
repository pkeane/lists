{extends file="base.tpl"}

{block name="title"}create a list{/block}

{block name="main"}
<div id="header">
    <form method="post">
        <h1>Edit Item</h1>
        <textarea name="text">{$item->text}</textarea>
        <input type="submit" value="update">
    </form>
</div>
<div id="footer">
    <a href="{$app_root}">home/lists</a>
</div>
{/block}
