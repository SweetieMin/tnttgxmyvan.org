# tnttgxmyvan.org

## MCP server (OnePanel hosting)

Repo có sẵn `.mcp.json` khai báo MCP server `onepanel-nsyqhidjhosting`, trỏ tới
panel hosting qua `mcp-remote`.

Token **không** được lưu trong repo. Trước khi mở Claude Code, đặt biến môi trường:

```bash
export ONEPANEL_MCP_TOKEN="sp_..."   # token Bearer lấy từ panel
```

Lần đầu chạy, Claude Code sẽ hỏi có tin tưởng MCP server trong `.mcp.json` không —
chọn đồng ý. Kiểm tra kết nối bằng lệnh `/mcp` trong Claude Code.

Nếu token bị lộ, thu hồi và tạo lại trong panel rồi cập nhật biến môi trường —
không cần sửa `.mcp.json`.
