<?php
// +----------------------------------------------------------------------
// | File create_time：2012-03-14
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: zhangrui <zhangrui_guitar@163.com>
// +----------------------------------------------------------------------


//百度团购导航API分类，二级分类只能选一个
//（分别对应——1代表"餐饮美食"，2代表"休闲娱乐"，3代表"生活服务"，4代表"网上购物"，5代表"酒店旅游"，6代表"丽人"）
$baidu = array(
	'餐饮美食' => array(
		'火锅' => array('无', '自助', '双人套餐', '多人聚餐'), 
		'西餐' => array('无', '自助', '双人套餐', '多人聚餐'), 
		'海鲜' => array('无', '自助', '双人套餐', '多人聚餐'), 
		'地方菜' => array('无', '自助', '双人套餐', '多人聚餐'), 
		'蛋糕' => array('无', '自助', '双人套餐', '多人聚餐'), 
		'烧烤' => array('无', '自助', '双人套餐', '多人聚餐'), 
		'日韩料理' => array('无', '自助', '双人套餐', '多人聚餐'), 
		'休闲快餐' => array('无', '自助', '双人套餐', '多人聚餐'), 
		'烤鱼' => array('无', '自助', '双人套餐', '多人聚餐'), 
		'麻辣香锅' => array('无', '自助', '双人套餐', '多人聚餐'), 
		'其他' => array('无', '自助', '双人套餐', '多人聚餐'), 
	),
	'休闲娱乐' => array('电影', 'KTV', '运动健身', '游乐电玩', '展览演出', '足疗按摩', '其他'),
	'丽人' => array('美发', '美容美体', '美甲', '其他'),
	'生活服务' => array('写真', '婚纱摄影', '儿童摄影', '口腔', '体检','教育培训','汽车养护','其他'),
	'旅游住宿' => array(
		'酒店' => array(),
		'旅游' => array('三亚', '北京', '上海', '成都', '广州', '深圳', '天津', '青岛', '安徽', '黄山', '丽江', '厦门', '西安', '杭州', '港澳', '境外', '其他'), 
		'景点公园' => array(),
	),
	'网上购物' => array(
		'服装' => array('男装', '女装', '童装', '内衣', '袜子', '美体塑身', '其他'),
		'鞋靴' => array('男鞋', '女鞋', '童鞋', '其他'), 
		'箱包' => array('女包-单肩包', '女包-手提包', '女包-斜挎包', '女包-双肩包', '女包-钱包', '男包-单肩包', '男包-手提包', '男包-钱包/手包', '功能箱包', '运动包', '其他'),
		'饰品' => array('眼镜', '围巾', '皮带', '首饰', '其他'),
		'化妆品' => array('面部保养', '眼唇保养', '彩妆', '身体护理', '香水', '美容工具', '其他'),
		'生活家居' => array('床上用品', '生活日用', '清洁用品', '厨具', '成人用品', '其他'),
		'家用电器' => array('生活电器', '厨房电器', '个人护理', '健康电器', '其他'),
		'手机数码' => array('手机', '手机配件', '摄影摄像', '电脑数码', '时尚影音', '其他'),
		'食品饮料' => array('保健品', '粮油蔬果', '零食', '茶酒饮料', '其他'),
		'母婴用品' => array('妈妈用品', '宝宝用品', '其他'),
		'手表' => array(),
		'玩具' => array(),
		'抽奖' => array(),
		'礼品' => array(),
		'其他' => array(),
	),
);


//360团购导航API分类，二级分类多个以空格分隔
$tuan360 = array(
	'餐饮美食' => array('中餐馆', '西餐馆', '日韩餐馆', '火锅烧烤', '自助', '地方菜系', '特色小吃', '下午茶', '咖啡馆', '蛋糕','冰淇淋', '其他'),
	'休闲娱乐' => array('摄影','电影票', '运动健身', '话剧 相声', '休闲会所', '游乐游戏', 'KTV', '户外运动', '度假村', '周末度假休闲', '酒吧', '其他'),
	'美容保健' => array('美发', '美容美体服务', '足疗保健', '美体养生', '健康护理', '其他'),
	'优惠券票' => array('酒店', '快捷酒店', '旅游团', '旅游代金券', '全国电子年票', '机票', '其他'),
	'精品购物' => array('服饰', '化妆品', '生活日用品', '玩具', '礼盒', '零食', '礼品卡', '箱包', '首饰', '鞋袜', '其他'),
	'其他' => array('汽车美容', '宠物美容', '其他'),
);


//团800团购导航API分类，多个以（半角逗号）','分隔
$tuan800 = array('美食', '娱乐', '邮购', '生活', '健身运动', '日常服务', '美容', '美发', '票务', '购物券卡', '其它', );


//sogou团购导航API分类（团购商品一级分类 1=餐饮美食，2=休闲娱乐，3=美容保健，4=网上购物，5=酒店旅游）
$sogou = array(
    '餐饮美食' => array('自助', '双人套餐', '火锅', '海鲜', '蛋糕甜点', '饮品', '西餐', '麻辣香锅', '烧烤', '烤鱼', '快餐休闲', '日韩料理', '地方菜', '其他'),
    '休闲娱乐' => array(
        '电影' => array(),
        'KTV' => array(),
        '摄影写真' => array('婚纱摄影', '儿童摄影', '艺术写真'),
        '郊游采摘' => array(),
        '运动健身' => array(),
        '游乐电玩' => array(),
        '演出活动' => array(),
        '洗浴' => array(),
        '其他' => array(),
    ),
    '美容保健' => array('美容美体', '美发', '足疗', '美甲', '口腔', '体检', '按摩', '其他'),
    '网上购物' => array(
        '夏季服饰热卖' => array('T恤', '连衣裙', '短裤', '凉鞋', '短裙'),
        '服装' => array('男装', '女装', '内衣', '童装', '袜子'),
        '化妆品' => array('香水', '面部保养', '眼唇保养', '身体护理', '彩妆', '美容工具', '其他'),
        '饰品' => array('首饰', '眼镜', '围巾', '腰带', '其他'),
        '箱包' => array('男包', '女包', '运动户外包', '功能箱包'),
        '鞋靴' => array('男鞋', '女鞋', '童鞋'),
        '手机数码' => array('手机', '手机配件', '摄影摄像', '电脑数码', '时尚影音', '其他'),
        '家用电器' => array('生活电器', '厨房电器', '个人护理', '健康电器', '其他'),
        '生活家居' => array('床上用品', '厨卫用品', '清洁用品', '生活日用', '成人用品'),
        '手表' => array(),
        '食品饮料' => array('零食', '茶酒饮料', '粮油蔬果', '保健品', '其他'),
        '玩具' => array(),
        '母婴用品' => array('妈妈用品', '宝宝用品', '其他'),
        '抽奖' => array(),
        '其他' => array(),
    ),
    '酒店旅游' => array('酒店住宿', '周边游', '国内游', '出境游', '景点门票', '其他'),
);

$jinshan = array(
		'餐饮美食' => array(
				'火锅' => array('无', '自助', '双人套餐', '多人聚餐'),
				'西餐' => array('无', '自助', '双人套餐', '多人聚餐'),
				'海鲜' => array('无', '自助', '双人套餐', '多人聚餐'),
				'地方菜' => array('无', '自助', '双人套餐', '多人聚餐'),
				'蛋糕' => array('无', '自助', '双人套餐', '多人聚餐'),
				'烧烤' => array('无', '自助', '双人套餐', '多人聚餐'),
				'日韩料理' => array('无', '自助', '双人套餐', '多人聚餐'),
				'快餐休闲' => array('无', '自助', '双人套餐', '多人聚餐'),
				'烤鱼' => array('无', '自助', '双人套餐', '多人聚餐'),
				'麻辣香锅' => array('无', '自助', '双人套餐', '多人聚餐'),
				'其他' => array('无', '自助', '双人套餐', '多人聚餐'),
		),
		'娱乐休闲' => array('电影', 'KTV', '运动健身', '游艺电玩', '展览演出', '足疗按摩', '其他'),
		'生活服务' => array('美发', '美容美体', '美甲', '写真', '婚纱摄影', '儿童摄影', '教育培训', '口腔', '体检', '汽车养护', '其他'),
		'旅游酒店' => array(
				'酒店' => array(),
				'旅游' => array('三亚', '北京', '上海', '成都', '广州', '深圳', '天津', '青岛', '安徽', '黄山', '丽江', '厦门', '西安', '杭州', '港澳', '境外', '其他'),
				'景点公园' => array(),
		),
		'商品团购' => array(
				'服装' => array('男装', '女装', '童装', '内衣', '袜子', '美体塑身', '其他'),
				'鞋靴' => array('男鞋', '女鞋', '童鞋', '运动鞋', '其他'),
				'箱包' => array('女包-单肩包', '女包-手提包', '女包-斜挎包', '女包-钱包', '男包-单肩包', '男包-手提包', '男包-钱包/手包', '运动包', '拉杆箱', '电脑包', '其他'),
				'饰品' => array('眼镜', '围巾', '皮带', '首饰', '其他'),
				'化妆品' => array('面部保养', '眼唇保养', '彩妆', '身体护理', '香水', '美容工具', '其他'),
				'生活家居' => array('床上用品', '厨具', '生活日用', '清洁用品', '成人用品', '其他'),
				'家用电器' => array('生活电器', '厨房电器', '个人护理', '健康电器', '其他'),
				'手机数码' => array('手机', '手机配件', '摄影摄像', '电脑数码', '时尚影音', '其他'),
				'食品饮料' => array('保健品', '粮油蔬果', '零食', '茶酒饮料', '其他'),
				'母婴用品' => array('妈妈用品', '宝宝用品', '其他'),
				'手表' => array(),
				'玩具' => array(),
				'礼品' => array(),
				'其他' => array(),
		),
		'0元抽奖' => array('0元抽奖'),
		
);
