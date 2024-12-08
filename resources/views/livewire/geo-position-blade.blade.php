<div>
   <div class="mb-3 d-flex flex-column flex-md-row align-items-md-center">
      <select class="form-control mr-md-2 mb-2 mb-md-0" id="selectvalue" wire:model="selectedLocation" style="height:38px">
      <option value="">Выберите или создайте геометку</option>
      @foreach($locations as $location)
      <option value="{{ $location }}">{{ $location }}</option>
      @endforeach
      </select>
      <button title="Delete" class="btn btn-sm btn-danger" wire:click="deleteLocation()" style="height:38px">Удалить</button>
   </div>
   <div class="form-group mt-4">
      <label for="name" class="text-body">Название геолокации:</label>
      <input type="text" class="form-control" id="name" wire:model.lazy="name" placeholder="Введите название геолокации">
      @error('name') <span class="text-danger">{{ $message }}</span>@enderror
   </div>
   <div class="row">
      <div class="col-md-6">
         <div class="form-group">
            <label for="latitude" class="text-body">Широта:</label>
            <input type="text" class="form-control" id="latitude" wire:model="latitude" oninput="this.value = this.value.replace(',', '.').replace(/[^\d.-]|(?<=\..*)\./g, '')" placeholder="Введите широту">
            @error('latitude') <span class="text-danger">{{ $message }}</span>@enderror
         </div>
      </div>
      <div class="col-md-6">
         <div class="form-group">
            <label for="longitude" class="text-body">Долгота:</label>
            <input type="text" class="form-control" id="longitude" wire:model="longitude" oninput="this.value = this.value.replace(',', '.').replace(/[^\d.-]|(?<=\..*)\./g, '')" placeholder="Введите долготу">
            @error('longitude') <span class="text-danger">{{ $message }}</span>@enderror
         </div>
      </div>
   </div>
<div class="mb-3 d-flex flex-column flex-md-row align-items-md-center">
   <input type="text" class="form-control mr-md-2 mb-2 mb-md-0" id="mapLink" placeholder="Вставьте ссылку на карту" wire:model.lazy="mapLink">
   <button class="btn btn-sm btn-primary" wire:click="getCoordinates">Заполнить</button>
</div>
   <div class="form-group">
      <label for="routeDescription" class="text-body">Описание маршрута:</label>
      <textarea class="form-control" id="routeDescription" rows="3" wire:model.lazy="routeDescription" placeholder="Введите описание маршрута"></textarea>
      @error('routeDescription') <span class="text-danger">{{ $message }}</span>@enderror
   </div>
   <button type="submit" class="btn btn-success text-dark" wire:click="saveLocation"><i class="fa fa-save text-dark"></i>&nbsp;Сохранить</button>
</div>
