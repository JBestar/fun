-- Slot game sync (SlotSync): run once before using `php public/index.php slotsync run`
-- Backup first:
--   CREATE TABLE slot_game_bak_YYYYMMDD AS SELECT * FROM slot_game;

ALTER TABLE slot_game
  ADD COLUMN sync_removed INT NOT NULL DEFAULT 0,
  ADD INDEX idx_cat_prd_code (cat, prd_code, game_code);
