<?php namespace Kahire\ViewSets;

use Kahire\ViewSets\Mixins\DeleteModelMixin;
use Kahire\ViewSets\Mixins\IndexModelMixin;
use Kahire\ViewSets\Mixins\ShowModelMixin;
use Kahire\ViewSets\Mixins\StoreModelMixin;
use Kahire\ViewSets\Mixins\UpdateModelMixin;

abstract class ModelViewSet extends GenericViewSet {

    use IndexModelMixin, ShowModelMixin, StoreModelMixin, UpdateModelMixin, DeleteModelMixin;
}