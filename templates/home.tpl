{extends file="base.tpl"}

{block name="title"}create a list{/block}

{block name="main"}
<div id="header">
    <form method="post">
        <h1>Create A List</h1>
        <input type="hidden" name="uniq" value="{$uniq}">
        <input type="text" name="name">
        <input type="submit" value="go">
    </form>
</div>
<div id="footer">
    <a href="{$app_root}">lists</a>
</div>
{/block}
