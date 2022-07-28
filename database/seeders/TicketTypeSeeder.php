<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TicketTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ticket_types')->insert([
            [
                'name' => "GÓI TẬP A.M",
                'description' => 'Gói tập phù hợp với các bạn cố định về mặt thời gian buổi sáng hoặc bạn muốn thử sức bền, độ kiên trì và kỉ luật cá nhân khi thức dậy sớm vào buổi sáng và luyện tập nghiêm túc. Bạn sẽ trải nghiệm đầy đủ cơ sở vật chất, chất lượng dịch vụ, không khí tập luyện máu lửa ở Swequity, cũng như nhìn thấy kết quả của sau quá trình rèn luyện kỉ luật.',
                'price' => '250000',
                'shift' =>  '2',
            ],
            [
                'name' => "GÓI TẬP P.M",
                'description' => 'Gói tập phù hợp với các bạn cố định về mặt thời gian buổi chiều tối hoặc bạn muốn thử sức bền và độ kiên trì và kỉ luật cá nhân sau một ngày làm việc làm việc. Bạn sẽ trải nghiệm đầy đủ cơ sở vật chất, chất lượng dịch vụ, không khí tập luyện máu lửa ở Swequity, cũng như nhìn thấy kết quả của sau quá trình rèn luyện kỉ luật.',
                'price' => '250000',
                'shift' =>  '3',
            ],
            [
                'name' => "GÓI TẬP FULL-TIME",
                'description' => 'Đây là gói tập phổ biến tại Swequity. Bạn có thể tập luyện linh hoạt và không bị giới hạn thời gian. Nếu là một người có tính kỉ luật và quyết tâm cao độ, có nhu cầu tập luyện trong môi trường đúng chất gym, gắn bó với một cộng đồng có trình độ và hiểu biết, mong muốn duy trì lối sống lành mạnh, với chi phí hiệu quả kinh tế nhất, thì đây sẽ là gói tập dành cho bạn.',
                'price' => '400000',
                'shift' =>  '1',
            ],
            [
                'name' => "GÓI TẬP V.I.P",
                'description' => 'Với gói tập V.I.P bạn có thể tập luyện tại mọi cơ sở của THFitness với khung thời gian linh hoạt tùy theo nhu cầu bản thân. Bạn không cần tập quá sức, bạn chỉ cần tập theo phương pháp chuẩn chỉnh để có được kết quả tốt nhất. Với phương pháp tập gym đúng chuẩn tại Swequity bạn sẽ nhận được kết quả xứng đáng với thời gian, công sức và tiền bạc mình bỏ ra.',
                'price' => '900000',
                'shift' =>  '1',
            ],
        ]);
    }
}
