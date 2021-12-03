# Laravel Help Desk application
Веб-приложение - служба техподдержки (Help Desk).
Представляет систему для учета заявок пользователей.

## В системе есть следующие сущности:
- Пользователи
- Заявки
- Комментарии (в заявках)


## Функции:
Регистрация (в роли клиентов) и авторизация пользователей
Управление заявками
Комментирование заявок
Изменение статуса заявок
Уведомления о смене статуса заявок, появлении новых заявок, установке/смене ответственного
Управление пользователями
"Мягкое" удаление пользователей и заявок

## Роли пользователей системы:
1) Клиент:  
- Может создавать заявки
- Может видеть заявки, которые сам создал
- Может оставлять комментарии в своих заявках
- Получает уведомления на почту, когда его заявка меняет статус
- Может закрыть заявку + она должна автоматически закрываться, если прошла неделя и не было никакой активности (не менялся статус и не было новых комментариев)
2) Менеджер:  
- Видит заявки, которые назначены на него
- Может брать "свободные" заявки (которые новые и ещё не были назначены на кого-то), то есть назначать на себя
- Может комментировать заявки, которые назначены на него. После написания комментария, можно автоматически менять статус заявки, например, на "Ожидает ответа клиента"
- Получает уведомления, когда появляются новые комментарии от клиента в заявках, назначенных на него. Также получает уведомление при назначении старшим менеджером завки на него.
3) Старший менеджер:  
- Видит все заявки
- Может управлять тем, на кого назначить заявки, в том числе имеет возможность переназначить на другого менеджера
- Получает уведомления о появлении новых заявок
4) Администратор:
- Имеет те же возможности, что и старший менеджер
- Может управлять пользователями: Добавлять/Изменять (в том числе менять роль)/Удалять
