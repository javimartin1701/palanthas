<div>
    <nav>
        <ul>
            <li><a href="{{ route('libros.index') }}">Listado de Libros</a></li>
            <li>
                <a href="#" wire:click="logout" wire:loading.attr="disabled">
                    Logout
                </a>
            </li>
        </ul>
    </nav>
</div>
