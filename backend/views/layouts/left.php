<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p>Alexander Pierce</p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form -->
        <form action="#" class="sidebar-form">
            <div class="input-group">
                <input name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <!-- /.search form -->

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu'],
                'items' => [
                    ['label' => 'Управление', 'options' => ['class' => 'header']],
                    ['label' => 'Магазин', 'icon' => 'folder', 'items' => [
                        ['label' => 'Товары', 'icon' => 'file-o', 'url' => ['/shop/product/index'], 'active' => $this->context->id == 'shop/product'],
                        ['label' => 'Бренды', 'icon' => 'file-o', 'url' => ['/shop/brand/index'], 'active' => $this->context->id == 'shop/brand'],
                        ['label' => 'Теги', 'icon' => 'file-o', 'url' => ['/shop/tag/index'], 'active' => $this->context->id == 'shop/tag'],
                        ['label' => 'Категории', 'icon' => 'file-o', 'url' => ['/shop/category/index'], 'active' => $this->context->id == 'shop/category'],
                        ['label' => 'Характеристики', 'icon' => 'file-o', 'url' => ['/shop/characteristic/index'], 'active' => $this->context->id == 'shop/characteristic'],
                    ]],
                    ['label' => 'Пользователи', 'icon' => 'user', 'url' => ['/user/index'], 'active' => $this->context->id == 'user'],
                ],
            ]
        ) ?>

    </section>

</aside>
