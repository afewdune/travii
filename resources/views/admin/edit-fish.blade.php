<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>บ่อปลา</title>
    @vite(['resources/css/app.scss','resources/js/app.js'])
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=person" />
</head>
<body>
    <div id="app">

    <a href="/"><img src="/storage/assets/logo.svg" alt="Logo" class="logo" width="130"></a>
    <header class="d-flex justify-content-between align-items-center p-4">
      <div>
        <div class="dropdown ms-2">
          <button class="dropdown-toggle" type="button" id="userMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="/storage/assets/person.svg"> {{ Auth::check() ? Auth::user()->username : 'Guest' }}
          </button>
          <ul class="dropdown-menu" aria-labelledby="userMenuButton">
          <li><a href="/login"><div v-if="!localUser" @click="navigateTo('')">เข้าสู่ระบบ</div></a></li>
            <li><a href="/login"><div v-if="!localUser" @click="navigateTo('/register')" class="">สมัครสมาชิก</div></a></li>
            <li><a href="/inventory"><div v-if="localUser" @click="navigateTo('/inventory')">ถังเก็บปลา</div></a></li>
            <li><a href="/" @click.prevent="logout"><div v-if="localUser">ออกจากระบบ</div></a></li>
            <li><a href="/admin"><div v-if="localUser && localUser.role === 'admin'">เข้าหลังบ้าน</div></a></li>
          </ul>
        </div>
      </div>
    </header>

    
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Edit Fish') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('admin.edit-fish', $fish->FishID) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="FishName" class="form-label">Fish Name:</label>
                            <input type="text" name="FishName" class="form-control" id="FishName" value="{{ $fish->FishName }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="FishRarity" class="form-label">Fish Rarity:</label>
                            <select name="FishRarity" class="form-control" id="FishRarity" required>
                                <option value="COMMON" {{ $fish->FishRarity == 'COMMON' ? 'selected' : '' }}>COMMON</option>
                                <option value="RARE" {{ $fish->FishRarity == 'RARE' ? 'selected' : '' }}>RARE</option>
                                <option value="SR" {{ $fish->FishRarity == 'SR' ? 'selected' : '' }}>SR</option>
                                <option value="SSR" {{ $fish->FishRarity == 'SSR' ? 'selected' : '' }}>SSR</option>
                                <option value="NFT" {{ $fish->FishRarity == 'NFT' ? 'selected' : '' }}>NFT</option>
                                <option value="SPECIAL" {{ $fish->FishRarity == 'SPECIAL' ? 'selected' : '' }}>SPECIAL</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="FishWorth" class="form-label">Fish Worth:</label>
                            <input type="number" name="FishWorth" class="form-control" id="FishWorth" value="{{ $fish->FishWorth }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="FishTokenWorth" class="form-label">Fish Token Worth:</label>
                            <input type="number" name="FishTokenWorth" class="form-control" id="FishTokenWorth" value="{{ $fish->FishTokenWorth }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="FishImage" class="form-label">Fish Image:</label>
                            <input type="file" name="FishImage" class="form-control" id="FishImage" accept="image/*">
                            @if($fish->FishImage)
                                <img src="{{ asset('storage/' . $fish->FishImage) }}" alt="{{ $fish->FishName }}" width="100">
                            @endif
                        </div>
                        <button type="submit" class="btn btn-primary">Update Fish</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>



</div>
    <effect></effect>
</body>

<script src="https://cdn.jsdelivr.net/npm/vue@2"></script>
    <script>
        new Vue({
            el: '#app',
            data: {
                localUser: {!! json_encode(Auth::user()) !!}
            },
            methods: {
                navigateTo(url) {
                    window.location.href = url;
                },
                logout() {
                    document.getElementById('logout-form').submit();
                }
            }
        });
    </script>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
    
</html>