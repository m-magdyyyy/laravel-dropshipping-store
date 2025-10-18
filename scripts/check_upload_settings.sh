#!/bin/bash

# ============================================================
# سكريبت التحقق من إعدادات رفع الملفات في PHP
# ============================================================

# الألوان
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
BLUE='\033[0;34m'
BOLD='\033[1m'
NC='\033[0m'

clear
echo -e "${BOLD}${BLUE}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo -e "${BOLD}${BLUE}   📊 فحص إعدادات رفع الملفات في PHP${NC}"
echo -e "${BOLD}${BLUE}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo ""

# العثور على ملف php.ini
PHP_INI=$(php --ini | grep "Loaded Configuration File" | cut -d: -f2 | xargs)
echo -e "${BOLD}📍 ملف php.ini:${NC} $PHP_INI"
echo ""

# الحصول على القيم الحالية
UPLOAD_MAX=$(php -r "echo ini_get('upload_max_filesize');")
POST_MAX=$(php -r "echo ini_get('post_max_size');")
EXEC_TIME=$(php -r "echo ini_get('max_execution_time');")
MEMORY=$(php -r "echo ini_get('memory_limit');")

# دالة للتحقق من القيمة
check_value() {
    local name=$1
    local current=$2
    local recommended=$3
    local unit=$4
    
    # تحويل القيم للمقارنة (بالميجابايت)
    if [[ $current =~ ^([0-9]+)M$ ]]; then
        current_mb=${BASH_REMATCH[1]}
    elif [[ $current =~ ^([0-9]+)K$ ]]; then
        current_mb=$((${BASH_REMATCH[1]} / 1024))
    elif [[ $current =~ ^([0-9]+)G$ ]]; then
        current_mb=$((${BASH_REMATCH[1]} * 1024))
    elif [[ $current =~ ^-?[0-9]+$ ]]; then
        current_mb=$current
    else
        current_mb=0
    fi
    
    if [[ $recommended =~ ^([0-9]+)M$ ]]; then
        recommended_mb=${BASH_REMATCH[1]}
    else
        recommended_mb=$recommended
    fi
    
    echo -en "${BOLD}${name}:${NC} "
    printf "%-15s" "$current"
    
    if [ "$current_mb" -ge "$recommended_mb" ] || [ "$current" == "-1" ]; then
        echo -e "${GREEN}✓ جيد${NC} (الموصى به: >=${recommended}${unit})"
    else
        echo -e "${RED}✗ منخفض${NC} (الموصى به: ${recommended}${unit})"
    fi
}

# عرض النتائج
echo -e "${BOLD}📋 الإعدادات الحالية:${NC}"
echo ""
check_value "  upload_max_filesize" "$UPLOAD_MAX" "50" "M"
check_value "  post_max_size      " "$POST_MAX" "64" "M"
check_value "  max_execution_time " "$EXEC_TIME" "120" "s"
check_value "  memory_limit       " "$MEMORY" "128" "M"
echo ""

# التحقق الشامل
UPLOAD_OK=false
POST_OK=false

if [[ $UPLOAD_MAX =~ ^([0-9]+)M$ ]] && [ "${BASH_REMATCH[1]}" -ge 50 ]; then
    UPLOAD_OK=true
fi

if [[ $POST_MAX =~ ^([0-9]+)M$ ]] && [ "${BASH_REMATCH[1]}" -ge 64 ]; then
    POST_OK=true
fi

echo -e "${BOLD}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
if $UPLOAD_OK && $POST_OK; then
    echo -e "${BOLD}${GREEN}✅ الإعدادات مثالية لرفع الملفات الكبيرة!${NC}"
    echo -e "   يمكنك الآن رفع ملفات حتى 50 ميجابايت"
else
    echo -e "${BOLD}${YELLOW}⚠️  الإعدادات تحتاج إلى تحسين${NC}"
    echo ""
    echo -e "لإصلاح الإعدادات تلقائياً، شغّل:"
    echo -e "${GREEN}./scripts/fix_upload_limits.sh${NC}"
fi
echo -e "${BOLD}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo ""

# معلومات إضافية
echo -e "${BLUE}💡 نصائح:${NC}"
echo "   • لرفع ملفات أكبر من 50MB، عدّل القيم في php.ini"
echo "   • تأكد من تشغيل Queue Worker لتحسين الصور"
echo "   • راجع PHP_UPLOAD_FIX_GUIDE.md لمزيد من المعلومات"
echo ""
