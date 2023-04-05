<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Panel Principal') }}
        </h2>
    </x-slot>

    <style>
        @media (prefers-color-scheme: dark) {
            label {
                color: white !important;
            }

            input, select {
                background-color: white !important;
                color: black !important;
            }

            button {
                background-color: rgba(28, 152, 131, 1) !important;
                color: white !important;
            }
        }

        button {
            background-color: rgba(28, 152, 131, 1) !important;
            color: white !important;
        }
    </style>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h2 style="font-weight:bolder;font-size: x-large; border-bottom: 1px solid; margin-bottom: 15px;
                    background-color: rgba(28, 152, 131, 1);padding: 15px;color:white;">
                        Insertar pagos
                    </h2>

                    <div class="w-full max-w-xl mx-auto">
                        <form action="/pagos/insertar" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-gray-700 font-bold mb-2" for="user">
                                    User
                                </label>
                                <select name="user[]" id="user" class="form-select block w-full mt-1">
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }} - {{ $user->id }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 font-bold mb-2" for="lote">
                                    Lote
                                </label>
                                <select name="lote[]" id="lote" class="form-select block w-full mt-1">
                                    @foreach ($lotes as $lote)
                                        <option value="{{ $lote->id }}">{{ $lote->nombre }} - {{ $lote->id }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 font-bold mb-2" for="cantidad">
                                    Cantidad
                                </label>
                                <input name="cantidad[]" id="cantidad" type="text" class="form-input block w-full mt-1" placeholder="0.00">
                            </div>
                            <div class="flex justify-between items-center">
                                <button type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" id="add-row-btn">
                                    +
                                </button>
                            </div>
                            <div id="rows-container"></div>
                            <div class="mt-4">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                    Submit
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        const users = {!! $users !!};
        const lotes = {!! $lotes !!};
        let rowId = 1;
        let lastRowId = 0;

        function createRow(id) {
            const html = `
      <div id="row-${id}">
        <div class="mb-4">
          <label class="block text-gray-700 font-bold mb-2" for="user-${id}">
            User
          </label>
          <select name="user[]" id="user-${id}" class="form-select block w-full mt-1">
            ${users.map(user => `<option value="${user.id}">${user.name} - ${user.id}</option>`).join('')}
          </select>
        </div>
        <div class="mb-4">
          <label class="block text-gray-700 font-bold mb-2" for="lote-${id}">
            Lote
          </label>
          <select name="lote[]" id="lote-${id}" class="form-select block w-full mt-1">
            ${lotes.map(lote => `<option value="${lote.id}">${lote.nombre} - ${lote.id}</option>`).join('')}
          </select>
        </div>
        <div class="mb-4">
          <label class="block text-gray-700 font-bold mb-2" for="cantidad-${id}">
            Cantidad
          </label>
          <input name="cantidad[]" id="cantidad-${id}" type="text" class="form-input block w-full mt-1" placeholder="0.00">
        </div>
        <div class="flex justify-between items-center">
          <button type="button" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" onclick="removeRow(${id})" ${id === 1 ? 'style="visibility: hidden;"' : ''}>
            -
          </button>
          <button type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" onclick="addRow()" ${id === lastRowId ? '' : 'style="visibility: hidden;"'}>
            +
          </button>
        </div>
      </div>
    `;
            return html;
        }

        function addRow() {
            const rowsContainer = document.getElementById('rows-container');
            const newRow = createRow(++rowId);
            rowsContainer.insertAdjacentHTML('beforeend', newRow);
            if (rowId > 1) {
                const lastRow = document.getElementById(`row-${lastRowId}`);
                const minusBtn = lastRow.querySelector('button:first-of-type');
                minusBtn.style.visibility = 'visible';
            }
            lastRowId = rowId;
        }

        function removeRow(id) {
            const row = document.getElementById(`row-${id}`);
            row.remove();
            if (id === lastRowId) {
                lastRowId--;
                const lastRow = document.getElementById(`row-${lastRowId}`);
                const plusBtn = lastRow.querySelector('button:last-of-type');
                plusBtn.style.visibility = 'visible';
            }
        }

        const addRowBtn = document.getElementById('add-row-btn');
        addRowBtn.addEventListener('click', addRow);
    </script>



</x-app-layout>

