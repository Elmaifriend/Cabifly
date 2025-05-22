<div class="max-w-4xl mx-auto p-6 space-y-8 pt-20"> <!-- Añadí pt-20 para dar espacio debajo navbar -->
    <!-- Navbar fija arriba -->
    <nav class="fixed top-0 left-0 right-0 bg-white shadow-md z-50">
        <div class="max-w-4xl mx-auto px-6 py-4">
            <h1 class="text-2xl font-bold text-black">FlyCab</h1>
        </div>
    </nav>

    @if ($step == 1)
        <div class="bg-white shadow-xl rounded-lg">
            <div class="p-6">
                <h2 class="text-2xl font-bold mb-6">Selecciona los productos</h2>

                @foreach ($categories as $category => $products)
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-4">{{ $category }}</h3>
                        <div class="grid grid-cols-2 gap-4">
                            @foreach ($products as $product)
                                @php
                                    $isSelected = in_array($product->id, $selectedProducts ?? []);
                                @endphp
                                <label for="product-{{ $product->id }}"
                                    class="h-[250px] cursor-pointer relative border rounded-lg p-3 flex flex-col items-center
                                        transition-shadow duration-200
                                        {{ $isSelected
                                            ? 'border-[#F6C90E] shadow-[0_4px_6px_-1px_rgba(246,201,14,0.5),0_2px_4px_-1px_rgba(246,201,14,0.06)]'
                                            : 'border-gray-300 hover:shadow-md' }}">
                                    <img src="https://picsum.photos/seed/{{ $product->name }}/200/300"
                                        alt="{{ $product->name }}" class="h-full w-full object-cover rounded-md mb-3" />

                                    <div class="text-center">
                                        <p class="font-medium">{{ $product->name }}</p>
                                        <p class="text-sm text-gray-600">${{ $product->price }}</p>
                                    </div>

                                    <input type="checkbox" id="product-{{ $product->id }}"
                                        wire:click="selectProduct({{ $product->id }})" class="hidden"
                                        {{ $isSelected ? 'checked' : '' }} />

                                    <div
                                        class="absolute top-2 right-2 w-6 h-6 bg-[#F6C90E] text-black rounded-full flex items-center justify-center
                                        transition-opacity duration-200
                                        {{ $isSelected ? 'opacity-100' : 'opacity-0' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                <div
                    class="sticky bottom-0 bg-white py-4 flex justify-between items-center border-t border-gray-200 px-6">
                    <p class="text-lg font-semibold">Total: ${{ $total }}</p>
                    <button
                        class="bg-[#F6C90E] hover:bg-yellow-400 text-black font-semibold py-2 px-6 rounded-lg disabled:opacity-50 disabled:cursor-not-allowed transition"
                        wire:click="$set('step', 2)" @disabled(!$selectedProducts)>
                        Siguiente
                    </button>
                </div>

            </div>
        </div>
    @elseif ($step == 2)
        <div class="bg-white shadow-xl rounded-lg">
            <div class="p-6">
                <h2 class="text-2xl font-bold">Selecciona tu ubicación en el mapa</h2>

                <div class="mt-4 rounded-lg overflow-hidden border border-gray-300">
                    <div wire:ignore x-data="{
                        map: null,
                        marker: null,
                        placeName: '',
                        async fetchPlaceName(lat, lon) {
                            try {
                                const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lon}`);
                                const data = await response.json();
                                return data.display_name || 'Nombre no disponible';
                            } catch {
                                return 'Error obteniendo el nombre';
                            }
                        },
                        async init() {
                            this.map = L.map(this.$refs.map).setView([51.1642, 10.4541194], 6);
                    
                            L.tileLayer(
                                'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap contributors' }
                            ).addTo(this.map);
                    
                            const waypointIcon = L.icon({
                                iconUrl: '{{ asset('img/waypoint.png') }}',
                                iconSize: [30, 40],
                                iconAnchor: [15, 40],
                                popupAnchor: [0, -40]
                            });
                    
                            this.map.on('click', async (e) => {
                                const { lat, lng } = e.latlng;
                    
                                if (this.marker) {
                                    this.map.removeLayer(this.marker);
                                }
                    
                                this.placeName = await this.fetchPlaceName(lat, lng);
                    
                                this.marker = L.marker([lat, lng], { icon: waypointIcon }).addTo(this.map)
                                    .bindPopup(this.placeName)
                                    .openPopup();
                            });
                        }
                    }" x-init="init()">
                        <div x-ref="map" class="w-full" style="height: 60vh;"></div>
                    </div>
                </div>

                <div class="flex justify-end mt-6">
                    <button
                        class="bg-[#F6C90E] hover:bg-yellow-400 text-black font-semibold py-2 px-6 rounded-lg transition"
                        wire:click="$set('step', 3)">
                        Siguiente
                    </button>
                </div>
            </div>
        </div>
    @elseif ($step == 3)
        <div class="bg-white shadow-xl rounded-lg">
            <div class="p-6">
                <h2 class="text-2xl font-bold">Resumen de tu orden</h2>

                <ul class="mt-4 space-y-1 list-disc list-inside">
                    @foreach ($selectedProducts as $productId)
                        @php
                            $product = \App\Models\Product::find($productId);
                        @endphp
                        <li>{{ $product->name }} - ${{ $product->price }}</li>
                    @endforeach
                </ul>

                <p class="mt-6 text-lg font-semibold">Total: ${{ $total }}</p>

                <div class="flex justify-end mt-4">
                    <button
                        class="bg-[#F6C90E] hover:bg-yellow-400 text-black font-semibold py-2 px-6 rounded-lg transition"
                        wire:click="submitOrder">
                        Confirmar Orden
                    </button>
                </div>
            </div>
        </div>
    @elseif ($step == 4)
        <div class="bg-[#F6C90E] text-black shadow-xl rounded-lg">
            <div class="p-6 text-center">
                <h2 class="text-2xl font-bold">¡Tu pedido está en camino!</h2>
                <p class="mt-2">Gracias por tu compra 🛻🌱</p>
                <div class="flex justify-center mt-4">
                    <button
                        class="border border-black font-semibold py-2 px-6 rounded-lg hover:bg-yellow-300 transition"
                        wire:click="$set('step', 1)">
                        Terminar
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
