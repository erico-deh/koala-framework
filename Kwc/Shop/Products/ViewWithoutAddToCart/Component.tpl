<div class="<?=$this->rootElementClass?>">
    <? if (isset($this->searchForm)) echo $this->component($this->searchForm); ?>
    <? if (isset($this->paging)) echo $this->component($this->paging); ?>
    <? if ($this->formSaved && !count($this->items)) { ?>
        <div class="noEntries"><?= $this->placeholder['noEntriesFound']; ?></div>
    <? } else { ?>
        <?=$this->partials($this->data)?>
    <? } ?>
    <? if (isset($this->paging)) echo $this->component($this->paging); ?>
</div>