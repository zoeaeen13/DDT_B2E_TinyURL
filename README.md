## 短網址服務設計
短網址，把普通的網址轉換成比較短的網址。因為字符變少，在字數限制的平台發文可編輯的字變多，而且更容易發布傳送，太長的網址容易截斷或不能被辨識為連結

- [Demo](http://oldfish.tw/tinyurl/)

![](https://i.imgur.com/bkMr7Zl.gif)


### 使用技術
- 使用 Bootstrap 和 jQuery 來做前端部分
- 後端以 PHP 完成
- 佈署在⾃⾏架設的 AWS EC2 Ubuntu 主機

![](https://i.imgur.com/dFH1CMT.jpg)

### 短網址系統設計
短網址系統設計看似簡單，裡頭包含眾多細節，關於資料儲存、算法、效能提升等等問題，圖中架構是比較完整的短網址設計。

![](https://i.imgur.com/Cpn43tO.png)

#### 短網址跳轉的基本原理
以 reurl 服務舉例，比如在瀏覽器輸入這一串短網址：`https://reurl.cc/9X0gnv`

1. 向 `https://reurl.cc` 的 IP 位置發送 GET 請求，查詢 `9X0gnv` 這串短碼
2. `https://reurl.cc` Server 去資料庫查到它對應的 URL，會通過轉址，重定向到使用者真正要訪問的伺服器

![](https://i.imgur.com/PeK7Pc2.png)


#### 實現短網址的生成
簡單來說，就是**將長網址和通過算法得到的短碼在資料庫中關聯起來**，比較好的算法有「自增序列算法」和「MurmurHash」兩種，因為作業時間有限就先以隨機產生代碼，之後會以這兩種算法來產生短碼的方向來優化。另外，有些參考資料中，有提到先檢查資料庫是否已經有該網址對應的短碼，這是關於一對一或是一對多的選擇，我的設計步驟沒有這部分，考量到一個長網址在不同時空背景、不同使用者的狀況下，應該要生成不一樣的短網址。

URL redirection 有幾種方式，可以使用 HTML 的轉址、Javascript 轉址或 PHP 的轉址，我這裡選擇由 PHP 回給我原始網址再以 JS 進行頁面跳轉。


### 參考資料
* [高性能短链设计](https://juejin.im/post/6844904090602848270)
* [短网址(short URL)系统的原理及其实现](https://hufangyun.com/2017/short-url/)
* [短网址系统(TinyURL)](https://www.bookstack.cn/read/system-design/cn-tinyurl.md)
* [短 URL 系统是怎么设计的？](https://www.zhihu.com/question/29270034)
