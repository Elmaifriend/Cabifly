<div>
    @if ($step == 1)
        <div>
            <h2>Selecciona los productos</h2>
            @foreach ($categories as $category => $products)
                <h3>{{ $category }}</h3>
                @foreach ($products as $product)
                    <div>
                        <input type="checkbox" wire:click="selectProduct({{ $product->id }})"
                               id="product-{{ $product->id }}" />
                        <label for="product-{{ $product->id }}">{{ $product->name }} - ${{ $product->price }}</label>
                    </div>
                @endforeach
            @endforeach
            <p>Total: ${{ $total }}</p>
            <button wire:click="$set('step', 2)" @disabled(!$selectedProducts)>Siguiente</button>
        </div>
    @elseif ($step == 2)
        <div>
            <h2>Selecciona tu ubicación en el mapa</h2>
            <div id="map" style="height:600px;"></div>
            <button wire:click="$set('step', 3)">Siguiente</button>
        </div>
    @elseif ($step == 3)
        <div>
            <h2>Resumen de tu orden</h2>
            <p><strong>Productos seleccionados:</strong></p>
            <ul>
                @foreach ($selectedProducts as $productId)
                    @php
                        $product = \App\Models\Product::find($productId);
                    @endphp
                    <li>{{ $product->name }} - ${{ $product->price }}</li>
                @endforeach
            </ul>
            <p><strong>Total: ${{ $total }}</strong></p>
            <button wire:click="submitOrder">Confirmar Orden</button>
        </div>
    @elseif ($step == 4)
        <div>
            <h2>¡Tu pedido está en camino!</h2>
            <button wire:click="$set('step', 1)">Terminar</button>
        </div>
    @endif
</div>

<script>
    document.addEventListener('livewire:load', function () {
        // Inicializar el mapa de Leaflet
        var map = L.map('map').setView([51.505, -0.09], 13); // Coordenadas por defecto

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Pin fijo en el centro
        var marker = L.marker([51.505, -0.09]).addTo(map);

        // Mover el pin con el mapa
        map.on('click', function (e) {
            marker.setLatLng(e.latlng);
            Livewire.emit('setLocation', e.latlng.lat, e.latlng.lng); // Emitir al componente Livewire
        });
    });
</script>
