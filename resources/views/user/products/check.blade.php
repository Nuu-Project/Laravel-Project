<!DOCTYPE html>
<html lang="en">
<head>
    <x-head />
</head>

<body class="font-body">
    <div class="flex flex-col md:flex-row h-screen bg-gray-100">
        <x-user-link />

        <!-- 主要內容區 -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <x-navbar-user />

            <!-- 主要內容 -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
                    <h3 class="text-gray-700 text-3xl font-medium mb-6">我的商品</h3>
                    
                    <div class="flex flex-col w-full min-h-screen">
                        <main class="flex min-h-[calc(100vh_-_theme(spacing.16))] flex-1 flex-col gap-4 p-4 md:gap-8 md:p-10">
                            @if($message)
                                <div class="alert alert-info text-lg font-semibold text-center text-blue-500 p-4">
                                    {{ $message }}
                                </div>
                            @endif    
                            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                                @foreach($userProducts as $product) 
                                    <div class="rounded-lg border bg-white text-card-foreground shadow-sm p-6" data-v0-t="card">
                                        <div class="space-y-2">
                                            <h4 class="font-semibold text-xl">商品名稱:{{$product->name}}</h4>
                                            <div><h1 class="font-semibold text-sm">用戶名稱:{{ $product->user->name }}</h1></div>
                                            @if($product->status == 100)
                                                <div><h1 class="font-semibold text-sm">目前狀態:上架中</h1></div>
                                            @elseif($product->status == 200)   
                                                <div><h1 class="font-semibold text-sm">目前狀態:已下架</h1></div>
                                            @else
                                                <div><h1 class="font-semibold text-sm">目前狀態:未知</h1></div>
                                            @endif
                                        </div>
                                        <div class="mt-4">
                                            <div class="text-2xl font-bold">${{$product->price}}</div>
                                            <h1 class="font-semibold text-sm mt-2">上架時間: {{$product->updated_at->format('Y/m/d')}}</h1>
                                            <p class="font-semibold text-sm mt-2">{{$product->description}}</p>
                                            <div class="mt-4">
                                                @if($product->media->isNotEmpty())
                                                    @php
                                                        $media = $product->getFirstMedia('images');
                                                    @endphp
                                                    @if($media)
                                                    <img src="{{ $media->getUrl() }}" alt="這是圖片" width="1200" height="900" style="aspect-ratio: 900 / 1200; object-fit: cover;" class="w-full rounded-md object-cover" />
                                                    @else
                                                        <div>沒圖片</div>
                                                    @endif
                                                @else
                                                    <div>沒有圖片</div>
                                                @endif
                                            </div>
                                        <div class="flex items-center justify-between mb-8">
                                        <h6 class="font-black text-gray-600 text-sm md:text-lg">年級 : 
                                            <span class="font-semibold text-gray-900 text-md md:text-lg">
                                            @php
                                                $gradeTag = $product->tags->firstWhere('type', '年級');
                                                $semesterTag = $product->tags->firstWhere('type', '學期');
                                            @endphp
                                            {{ $gradeTag ? $gradeTag->getTranslation('name', 'zh') : '無' }}
                                            {{ $semesterTag ? $semesterTag->getTranslation('name', 'zh') : '學期:無' }}
                                            </span>
                                        </h6>
                                        <h6 class="font-black text-gray-600 text-sm md:text-lg">課程 : 
                                            <span class="font-semibold text-gray-900 text-md md:text-lg">
                                                @php
                                                    $categoryTag = $product->tags->firstWhere('type', '課程');
                                                @endphp
                                                {{ $categoryTag ? $categoryTag->getTranslation('name', 'zh') : '無' }}
                                            </span>
                                        </h6>
                             </div>
                                        </div>
                                        <div class="flex justify-center space-x-4 mt-6">
                                            <form action="{{ route('products.demoteData', ['product' => $product->id])  }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                <input type="hidden" name="status" value="200"> 
                                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition ease-in-out duration-500">
                                                    下架
                                                </button>
                                            </form>
                                            <a href="{{ route('products.edit', ['product' => $product->id])  }}" class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition ease-in-out duration-500">
                                                編輯
                                            </a>
                                            <a href="" class="px-4 py-2 bg-red-600 text-white text-sm font-semibold rounded-xl hover:bg-red-700 transition ease-in-out duration-500">
                                                刪除
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-6">
                                {{ $userProducts->links() }}
                            </div>
                        </main>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
