#!/bin/bash

# ============================================================
# سكريبت أتمتة إصلاح إعدادات رفع الملفات في PHP
# يقوم بتعديل php.ini تلقائياً باستخدام sed
# ============================================================

set -e  # إيقاف التنفيذ عند حدوث خطأ

# الألوان
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
BLUE='\033[0;34m'
BOLD='\033[1m'
NC='\033[0m' # No Color

# رسالة الترحيب
clear
echo -e "${BOLD}${BLUE}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo -e "${BOLD}${BLUE}   🔧 سكريبت إصلاح إعدادات رفع الملفات في PHP${NC}"
echo -e "${BOLD}${BLUE}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo ""

# ====================================
# الخطوة 1: العثور على ملف php.ini
# ====================================
echo -e "${BOLD}📍 الخطوة 1: تحديد موقع ملف php.ini...${NC}"
PHP_INI=$(php --ini | grep "Loaded Configuration File" | cut -d: -f2 | xargs)

if [ -z "$PHP_INI" ] || [ ! -f "$PHP_INI" ]; then
    echo -e "${RED}❌ خطأ: لم يتم العثور على ملف php.ini${NC}"
    echo "تأكد من تثبيت PHP بشكل صحيح"
    exit 1
fi

echo -e "${GREEN}✓ تم العثور على ملف php.ini:${NC} $PHP_INI"
echo ""

# ====================================
# الخطوة 2: عرض الإعدادات الحالية
# ====================================
echo -e "${BOLD}📊 الخطوة 2: الإعدادات الحالية...${NC}"
CURRENT_UPLOAD=$(php -r "echo ini_get('upload_max_filesize');")
CURRENT_POST=$(php -r "echo ini_get('post_max_size');")
CURRENT_EXEC=$(php -r "echo ini_get('max_execution_time');")

echo -e "  upload_max_filesize: ${YELLOW}${CURRENT_UPLOAD}${NC}"
echo -e "  post_max_size:       ${YELLOW}${CURRENT_POST}${NC}"
echo -e "  max_execution_time:  ${YELLOW}${CURRENT_EXEC}${NC}"
echo ""

# ====================================
# الخطوة 3: تأكيد المتابعة
# ====================================
echo -e "${BOLD}⚙️  الإعدادات الجديدة التي سيتم تطبيقها:${NC}"
echo -e "  upload_max_filesize: ${GREEN}50M${NC}"
echo -e "  post_max_size:       ${GREEN}64M${NC}"
echo -e "  max_execution_time:  ${GREEN}120${NC}"
echo ""
echo -e "${YELLOW}⚠️  تحذير: هذا السكريبت سيقوم بتعديل ملف php.ini${NC}"
echo -e "${YELLOW}   سيتم إنشاء نسخة احتياطية قبل التعديل${NC}"
echo ""
read -p "هل تريد المتابعة؟ (y/n): " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    echo -e "${RED}تم الإلغاء${NC}"
    exit 0
fi
echo ""

# ====================================
# الخطوة 4: إنشاء نسخة احتياطية
# ====================================
echo -e "${BOLD}💾 الخطوة 3: إنشاء نسخة احتياطية...${NC}"
BACKUP_FILE="${PHP_INI}.backup.$(date +%Y%m%d_%H%M%S)"

if sudo cp "$PHP_INI" "$BACKUP_FILE"; then
    echo -e "${GREEN}✓ تم إنشاء نسخة احتياطية:${NC} $BACKUP_FILE"
else
    echo -e "${RED}❌ فشل إنشاء النسخة الاحتياطية${NC}"
    exit 1
fi
echo ""

# ====================================
# الخطوة 5: تعديل php.ini باستخدام sed
# ====================================
echo -e "${BOLD}🔧 الخطوة 4: تعديل إعدادات php.ini...${NC}"

# دالة لتعديل القيمة في php.ini
update_php_ini() {
    local setting=$1
    local new_value=$2
    local file=$3
    
    # البحث عن السطر وتعديله (سواء كان معلق عليه أو لا)
    if sudo sed -i.tmp \
        -e "s/^;\?[[:space:]]*${setting}[[:space:]]*=.*/${setting} = ${new_value}/" \
        "$file"; then
        echo -e "${GREEN}  ✓ تم تحديث ${setting} = ${new_value}${NC}"
        return 0
    else
        echo -e "${RED}  ✗ فشل تحديث ${setting}${NC}"
        return 1
    fi
}

# تعديل القيم الثلاثة
update_php_ini "upload_max_filesize" "50M" "$PHP_INI"
update_php_ini "post_max_size" "64M" "$PHP_INI"
update_php_ini "max_execution_time" "120" "$PHP_INI"

# حذف الملفات المؤقتة
sudo rm -f "${PHP_INI}.tmp"

echo ""

# ====================================
# الخطوة 6: إنشاء مجلدات Laravel
# ====================================
echo -e "${BOLD}📁 الخطوة 5: إعداد مجلدات Laravel...${NC}"

# إنشاء مجلد livewire-tmp
if [ ! -d "storage/app/livewire-tmp" ]; then
    mkdir -p storage/app/livewire-tmp
    echo -e "${GREEN}  ✓ تم إنشاء storage/app/livewire-tmp${NC}"
else
    echo -e "${YELLOW}  ⚠ المجلد storage/app/livewire-tmp موجود بالفعل${NC}"
fi

# ضبط الصلاحيات
chmod -R 775 storage/app/livewire-tmp 2>/dev/null && echo -e "${GREEN}  ✓ تم ضبط صلاحيات livewire-tmp${NC}"
chmod -R 775 storage/app/public 2>/dev/null && echo -e "${GREEN}  ✓ تم ضبط صلاحيات public${NC}"
chmod -R 775 storage/logs 2>/dev/null && echo -e "${GREEN}  ✓ تم ضبط صلاحيات logs${NC}"

echo ""

# ====================================
# الخطوة 7: التحقق من التعديلات
# ====================================
echo -e "${BOLD}✅ الخطوة 6: التحقق من التعديلات...${NC}"

# إعادة قراءة القيم الجديدة من php.ini مباشرة
NEW_UPLOAD=$(grep "^upload_max_filesize" "$PHP_INI" | cut -d'=' -f2 | xargs)
NEW_POST=$(grep "^post_max_size" "$PHP_INI" | cut -d'=' -f2 | xargs)
NEW_EXEC=$(grep "^max_execution_time" "$PHP_INI" | cut -d'=' -f2 | xargs)

echo -e "  القيم الجديدة في php.ini:"
echo -e "    upload_max_filesize: ${GREEN}${NEW_UPLOAD}${NC}"
echo -e "    post_max_size:       ${GREEN}${NEW_POST}${NC}"
echo -e "    max_execution_time:  ${GREEN}${NEW_EXEC}${NC}"
echo ""

# ====================================
# رسالة النجاح النهائية
# ====================================
echo -e "${BOLD}${GREEN}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo -e "${BOLD}${GREEN}   ✨ تم تحديث الإعدادات بنجاح!${NC}"
echo -e "${BOLD}${GREEN}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo ""
echo -e "${BOLD}${YELLOW}⚠️  خطوات مهمة:${NC}"
echo -e "   ${BOLD}1.${NC} أوقف سيرفر Laravel الحالي (اضغط Ctrl+C)"
echo -e "   ${BOLD}2.${NC} أعد تشغيل السيرفر: ${GREEN}php artisan serve${NC}"
echo -e "   ${BOLD}3.${NC} جرّب رفع الملفات الكبيرة في Filament"
echo ""
echo -e "${BLUE}💡 ملاحظة:${NC} تم حفظ نسخة احتياطية في:"
echo -e "   ${BACKUP_FILE}"
echo ""
echo -e "${BLUE}📋 للتحقق من الإعدادات الجديدة بعد إعادة التشغيل:${NC}"
echo -e "   ${GREEN}php -i | grep -E \"(upload_max_filesize|post_max_size|max_execution_time)\"${NC}"
echo ""
echo -e "${BOLD}${GREEN}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
