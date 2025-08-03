<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BibleSeeder extends Seeder
{
    public function run()
    {
        $bibleVerses = [
            "Ta há chẳng đã truyền lệnh cho ngươi sao? Hãy mạnh mẽ và can đảm; đừng sợ hãi; đừng nản lòng, vì Giê-hô-va Đức Chúa Trời ngươi sẽ ở cùng ngươi bất cứ nơi nào ngươi đi” (Giô-suê 1:9).",
            "Ngài phán: “Hãy yên lặng và biết rằng Ta là Đức Chúa Trời; Tôi sẽ được tôn cao giữa các nước, Tôi sẽ được tôn cao trên đất” (Thi Thiên 46:10).",
            "Trong lúc sợ hãi, tôi tin cậy nơi Chúa (Thi Thiên 56:3).",
            "Chúa đã làm điều đó ngay hôm nay; Chúng ta hãy vui mừng và hân hoan ngày hôm nay (Thi Thiên 118:24).",
            "Lời Chúa là ngọn đèn cho chân tôi, ánh sáng trên đường tôi đi (Thi Thiên 119:105).",
            "Hãy hết lòng tin cậy Chúa và đừng nương cậy vào sự thông sáng của mình; trong mọi đường lối của bạn hãy phục tùng Ngài, và Ngài sẽ chỉ dẫn các nẻo của con ngay thẳng (Châm Ngôn 3:5-6).",
            "Đức Chúa Jêsus đáp rằng: “Có lời chép rằng: Người ta sống chẳng phải chỉ nhờ bánh mà thôi, song nhờ mọi lời nói ra từ miệng Đức Chúa Trời” (Ma-thi-ơ 4:4).",
            "Quả thật, quả thật, ta bảo các ngươi, ai tin thì được sự sống đời đời (Giăng 6:47).",
            "Họ trả lời: “Hãy tin Chúa Jêsus, thì ngươi và cả nhà ngươi đều sẽ được cứu” (Công vụ 16:31).",
            "Vậy, Chúa tức là Thánh Linh, nơi nào có Thánh Linh của Chúa thì nơi đó có sự tự do (2 Cô-rinh-tô 3:17).",
            "Tôi đã bị đóng đinh với Đấng Christ và tôi không còn sống nữa, nhưng Đấng Christ sống trong tôi. Cuộc sống mà tôi hiện đang sống trong thân thể, tôi sống bởi đức tin vào Con Đức Chúa Trời, là Đấng đã yêu tôi và phó chính mình Ngài vì tôi (Ga-la-ti 2:20).",
            "“…Vì chúng ta bước đi bởi đức tin, chứ không phải bởi mắt thấy” (2 Cô-rinh-tô 5:7).",
            "Vì Đức Chúa Trời đã ban cho chúng ta Thánh Linh, không phải để làm chúng ta nhút nhát, nhưng để ban cho chúng ta sức mạnh, tình yêu thương và sự tự chủ (2 Ti-mô-thê 1:7).",
            "Đây là sứ điệp chúng tôi đã nghe từ Ngài và công bố cho anh em: Đức Chúa Trời là sự sáng; trong Ngài không có chút bóng tối nào (1 Giăng 1:5).",
            "Không có đức tin, thì chẳng hề có thế nào làm đẹp lòng Đức Chúa Trời; vì kẻ đến gần Ngài phải tin rằng có Đức Chúa Trời, và Ngài là Đấng hay thưởng cho kẻ tìm kiếm Ngài (Hê-bơ-rơ 11:6)."
        ];

        foreach ($bibleVerses as $verse) {
            DB::table('bibles')->insert([
                'bible' => $verse,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
